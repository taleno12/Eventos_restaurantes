<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Gastrobar;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    // ── INDEX ────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Contrato::with(['gastrobar', 'restaurante'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('numero_contrato', 'like', '%' . $request->search . '%')
                  ->orWhereHas('gastrobar', fn($q) => $q->where('nombre', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('restaurante', fn($q) => $q->where('nombre', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        $contratos    = $query->paginate(10);
        $total        = Contrato::count();
        $activos      = Contrato::where('estado', 'activo')->count();
        $vencidos     = Contrato::where('estado', 'vencido')->count();
        $pendientes   = Contrato::where('estado', 'pendiente')->count();
        $gastrobares  = Gastrobar::orderBy('nombre')->get();
        $restaurantes = Restaurante::orderBy('nombre')->get();

        return view('contratos.index', compact(
            'contratos', 'total', 'activos', 'vencidos', 'pendientes',
            'gastrobares', 'restaurantes'
        ));
    }

    // ── CREATE ───────────────────────────────────────────────────
    public function create()
    {
        $gastrobares  = Gastrobar::orderBy('nombre')->get();
        $restaurantes = Restaurante::orderBy('nombre')->get();

        return view('contratos.create', compact('gastrobares', 'restaurantes'));
    }

    // ── STORE ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tipo_establecimiento' => 'required|in:gastrobar,restaurante',
            'gastrobar_id'         => 'required_if:tipo_establecimiento,gastrobar|nullable|exists:gastrobares,id',
            'restaurante_id'       => 'required_if:tipo_establecimiento,restaurante|nullable|exists:restaurantes,id',
            'representante'        => 'required|string|max:255',
            'direccion'            => 'nullable|string|max:255',
            'plan'                 => 'required|in:gratuito,basico,premium',
            'fecha_inicio'         => 'required|date',
            'fecha_fin'            => 'required|date|after:fecha_inicio',
            'monto'                => 'nullable|numeric|min:0',
            'forma_pago'           => 'nullable|in:mensual,trimestral,anual',
            'estado'               => 'required|in:activo,vencido,pendiente,cancelado',
            'acepta_terminos'      => 'required|accepted',
            'acepta_privacidad'    => 'required|accepted',
        ], [
            'tipo_establecimiento.required' => 'Debes seleccionar el tipo de establecimiento.',
            'gastrobar_id.required_if'      => 'Debes seleccionar un gastrobar.',
            'restaurante_id.required_if'    => 'Debes seleccionar un restaurante.',
            'representante.required'        => 'El representante legal es obligatorio.',
            'plan.required'                 => 'Debes seleccionar un plan.',
            'fecha_inicio.required'         => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'            => 'La fecha de vencimiento es obligatoria.',
            'fecha_fin.after'               => 'La fecha de vencimiento debe ser posterior a la de inicio.',
            'acepta_terminos.accepted'      => 'Debes aceptar los Términos y Condiciones.',
            'acepta_privacidad.accepted'    => 'Debes aceptar la Política de Privacidad.',
        ]);

        $ultimo = Contrato::max('id') ?? 0;
        $numero = 'CON-' . str_pad($ultimo + 1, 4, '0', STR_PAD_LEFT);

        Contrato::create([
            'numero_contrato' => $numero,
            'gastrobar_id'    => $request->tipo_establecimiento === 'gastrobar'  ? $request->gastrobar_id  : null,
            'restaurante_id'  => $request->tipo_establecimiento === 'restaurante' ? $request->restaurante_id : null,
            'representante'   => $request->representante,
            'direccion'       => $request->direccion,
            'plan'            => $request->plan,
            'fecha_inicio'    => $request->fecha_inicio,
            'fecha_fin'       => $request->fecha_fin,
            'monto'           => $request->monto ?? 0,
            'forma_pago'      => $request->forma_pago,
            'estado'          => $request->estado,
        ]);

        return redirect()->route('contratos.index')
            ->with('success', "Contrato {$numero} registrado correctamente.");
    }

    // ── SHOW ─────────────────────────────────────────────────────
    public function show(Contrato $contrato)
    {
        $contrato->load(['gastrobar', 'restaurante']);
        return view('contratos.show', compact('contrato'));
    }

    // ── EDIT ─────────────────────────────────────────────────────
    public function edit(Contrato $contrato)
    {
        $gastrobares  = Gastrobar::orderBy('nombre')->get();
        $restaurantes = Restaurante::orderBy('nombre')->get();

        return view('contratos.edit', compact('contrato', 'gastrobares', 'restaurantes'));
    }

    // ── UPDATE ───────────────────────────────────────────────────
    public function update(Request $request, Contrato $contrato)
    {
        $request->validate([
            'tipo_establecimiento' => 'required|in:gastrobar,restaurante',
            'gastrobar_id'         => 'required_if:tipo_establecimiento,gastrobar|nullable|exists:gastrobares,id',
            'restaurante_id'       => 'required_if:tipo_establecimiento,restaurante|nullable|exists:restaurantes,id',
            'representante'        => 'required|string|max:255',
            'direccion'            => 'nullable|string|max:255',
            'plan'                 => 'required|in:gratuito,basico,premium',
            'fecha_inicio'         => 'required|date',
            'fecha_fin'            => 'required|date|after:fecha_inicio',
            'monto'                => 'nullable|numeric|min:0',
            'forma_pago'           => 'nullable|in:mensual,trimestral,anual',
            'estado'               => 'required|in:activo,vencido,pendiente,cancelado',
        ]);

        $contrato->update([
            'gastrobar_id'   => $request->tipo_establecimiento === 'gastrobar'  ? $request->gastrobar_id  : null,
            'restaurante_id' => $request->tipo_establecimiento === 'restaurante' ? $request->restaurante_id : null,
            'representante'  => $request->representante,
            'direccion'      => $request->direccion,
            'plan'           => $request->plan,
            'fecha_inicio'   => $request->fecha_inicio,
            'fecha_fin'      => $request->fecha_fin,
            'monto'          => $request->monto ?? 0,
            'forma_pago'     => $request->forma_pago,
            'estado'         => $request->estado,
        ]);

        return redirect()->route('contratos.index')
            ->with('success', "Contrato {$contrato->numero_contrato} actualizado correctamente.");
    }

    // ── DESTROY ──────────────────────────────────────────────────
    public function destroy(Contrato $contrato)
    {
        $numero = $contrato->numero_contrato;
        $contrato->delete();

        return redirect()->route('contratos.index')
            ->with('success', "Contrato {$numero} eliminado correctamente.");
    }
}
