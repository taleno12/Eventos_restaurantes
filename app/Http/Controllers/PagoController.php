<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Contrato;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    // ── INDEX ─────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Pago::with(['contrato.restaurante', 'contrato.gastrobar'])
                     ->latest('fecha_pago');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('numero_pago', 'like', "%{$buscar}%")
                  ->orWhere('referencia', 'like', "%{$buscar}%")
                  ->orWhereHas('contrato', fn($c) => $c->where('numero_contrato', 'like', "%{$buscar}%"))
                  ->orWhereHas('contrato.restaurante', fn($c) => $c->where('nombre', 'like', "%{$buscar}%"))
                  ->orWhereHas('contrato.gastrobar', fn($c) => $c->where('nombre', 'like', "%{$buscar}%"));
            });
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_pago', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_pago', '<=', $request->fecha_hasta);
        }

        $pagos            = $query->paginate(15)->withQueryString();
        $contratosActivos = Contrato::with(['restaurante', 'gastrobar'])
                                ->whereIn('estado', ['activo', 'pendiente'])
                                ->latest()
                                ->get();

        $departamentos = Departamento::orderBy('nombre')->get();

        $totalRecaudado  = Pago::pagados()->sum('monto');
        $totalPagados    = Pago::pagados()->count();
        $totalPendientes = Pago::pendientes()->count();
        $totalAnulados   = Pago::anulados()->count();
        $recaudadoMes    = Pago::pagados()
                               ->whereMonth('fecha_pago', now()->month)
                               ->whereYear('fecha_pago', now()->year)
                               ->sum('monto');

        return view('pagos.index', compact(
            'pagos',
            'contratosActivos',
            'departamentos',
            'totalRecaudado',
            'totalPagados',
            'totalPendientes',
            'totalAnulados',
            'recaudadoMes'
        ));
    }

    // ── AJAX: MUNICIPIOS POR DEPARTAMENTO ──────────────────────────

    public function municipiosPorDepartamento(Departamento $departamento)
    {
        $municipios = $departamento->municipios()
                        ->orderBy('nombre')
                        ->get(['id', 'nombre']);

        return response()->json($municipios);
    }

    // ── AJAX: CONTRATOS POR MUNICIPIO ───────────────────────────────

    public function contratosPorMunicipio(Request $request)
    {
        $request->validate([
            'municipio_id' => 'required|exists:municipios,id',
        ]);

        $municipioId = $request->query('municipio_id');

        $contratos = Contrato::with(['restaurante', 'gastrobar'])
            ->whereIn('estado', ['activo', 'pendiente'])
            ->where(function ($q) use ($municipioId) {
                $q->whereHas('restaurante', fn($r) => $r->where('municipio_id', $municipioId))
                  ->orWhereHas('gastrobar', fn($g) => $g->where('municipio_id', $municipioId));
            })
            ->latest()
            ->get()
            ->map(function ($contrato) {
                $est = $contrato->establecimiento();

                return [
                    'id'              => $contrato->id,
                    'numero_contrato' => $contrato->numero_contrato,
                    'nombre'          => $est ? $est->nombre : 'Sin establecimiento',
                    'tipo'            => $contrato->tipoEstablecimiento(),
                    'plan'            => $contrato->plan,
                ];
            });

        return response()->json($contratos);
    }

    // ── STORE ─────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contrato_id'    => 'required|exists:contratos,id',
            'monto'          => 'required|numeric|min:0.01',
            'metodo_pago'    => 'required|in:efectivo,transferencia,tarjeta,deposito',
            'referencia'     => 'nullable|string|max:100',
            'fecha_pago'     => 'required|date',
            'periodo_inicio' => 'nullable|date',
            'periodo_fin'    => 'nullable|date|after_or_equal:periodo_inicio',
            'estado'         => 'required|in:pagado,pendiente,anulado',
            'notas'          => 'nullable|string|max:500',
        ]);

        $validated['numero_pago']    = Pago::generarNumeroPago();
        $validated['registrado_por'] = Auth::id();

        Pago::create($validated);

        // ── Actualizar membresia_vence_en en el restaurante o gastrobar ──
        if ($validated['estado'] === 'pagado' && !empty($validated['periodo_fin'])) {
            $contrato = Contrato::with(['restaurante', 'gastrobar'])->find($validated['contrato_id']);

            if ($contrato->restaurante_id && $contrato->restaurante) {
                $contrato->restaurante->update([
                    'membresia_vence_en' => $validated['periodo_fin'],
                    'membresia_plan'     => $contrato->plan,
                ]);
            } elseif ($contrato->gastrobar_id && $contrato->gastrobar) {
                $contrato->gastrobar->update([
                    'membresia_vence_en' => $validated['periodo_fin'],
                    'membresia_plan'     => $contrato->plan,
                ]);
            }
        }

        return redirect()->route('pagos.index')
                         ->with('success', 'Pago registrado correctamente.');
    }

    // ── SHOW ──────────────────────────────────────────────────────

    public function show(Pago $pago)
    {
        $pago->load(['contrato.restaurante', 'contrato.gastrobar', 'registradoPor']);
        return view('pagos.show', compact('pago'));
    }

    // ── DESCARGAR PDF ─────────────────────────────────────────────

    public function descargarPdf(Pago $pago)
    {
        $pago->load(['contrato.restaurante', 'contrato.gastrobar', 'registradoPor']);

        $pdf = Pdf::loadView('pagos.recibo_pdf', compact('pago'));

        return $pdf->download("recibo-{$pago->numero_pago}.pdf");
    }

    // ── UPDATE ESTADO (anular/confirmar) ──────────────────────────

    public function updateEstado(Request $request, Pago $pago)
    {
        $request->validate([
            'estado' => 'required|in:pagado,pendiente,anulado',
        ]);

        $pago->update(['estado' => $request->estado]);

        // ── Si se confirma el pago, actualizar membresia_vence_en ──
        if ($request->estado === 'pagado' && $pago->periodo_fin) {
            $contrato = $pago->contrato()->with(['restaurante', 'gastrobar'])->first();

            if ($contrato->restaurante_id && $contrato->restaurante) {
                $contrato->restaurante->update([
                    'membresia_vence_en' => $pago->periodo_fin,
                    'membresia_plan'     => $contrato->plan,
                ]);
            } elseif ($contrato->gastrobar_id && $contrato->gastrobar) {
                $contrato->gastrobar->update([
                    'membresia_vence_en' => $pago->periodo_fin,
                    'membresia_plan'     => $contrato->plan,
                ]);
            }
        }

        return back()->with('success', 'Estado del pago actualizado.');
    }

    // ── DESTROY ───────────────────────────────────────────────────

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index')
                         ->with('success', 'Pago eliminado correctamente.');
    }
}
