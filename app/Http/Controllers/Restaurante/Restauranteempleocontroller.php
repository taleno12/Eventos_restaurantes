<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Empleo;
use App\Models\Municipio;
use App\Services\FcmNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestauranteEmpleoController extends Controller
{
    private FcmNotificationService $fcm;

    public function __construct(FcmNotificationService $fcm)
    {
        $this->fcm = $fcm;
    }

    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    public function index()
    {
        $restaurante = $this->restaurante();
        $empleos = Empleo::where('restaurante_id', $restaurante->id)
            ->latest()->paginate(10);
        return view('restaurante.empleos.index', compact('restaurante', 'empleos'));
    }

    public function create()
    {
        $restaurante = $this->restaurante();
        $municipios  = Municipio::where('departamento_id', $restaurante->departamento_id)
            ->orderBy('nombre')->get();
        return view('restaurante.empleos.create', compact('restaurante', 'municipios'));
    }

    public function store(Request $request)
    {
        $restaurante = $this->restaurante();

        $request->validate([
            'titulo'        => 'required|string|max:200',
            'descripcion'   => 'required|string',
            'requisitos'    => 'nullable|string',
            'tipo_contrato' => 'nullable|string|max:50',
            'salario'       => 'nullable|numeric|min:0',
            'fecha_limite'  => 'nullable|date|after:today',
            'municipio_id'  => 'required|exists:municipios,id',
        ]);

        $empleo = Empleo::create([
            ...$request->except('activo'),
            'activo'          => $request->boolean('activo', true),
            'restaurante_id'  => $restaurante->id,
            'departamento_id' => $restaurante->departamento_id,
        ]);

        $this->fcm->enviar(
            '💼 Nueva oferta de empleo',
            "¡{$empleo->titulo} está disponible en {$restaurante->nombre}!"
        );

        return redirect()->route('restaurante.empleos.index')
            ->with('success', '¡Oferta de empleo publicada!');
    }

    public function edit(Empleo $empleo)
    {
        $restaurante = $this->restaurante();
        abort_unless($empleo->restaurante_id === $restaurante->id, 403);

        $municipios = Municipio::where('departamento_id', $restaurante->departamento_id)
            ->orderBy('nombre')->get();

        return view('restaurante.empleos.edit', compact('restaurante', 'empleo', 'municipios'));
    }

    public function update(Request $request, Empleo $empleo)
    {
        $restaurante = $this->restaurante();
        abort_unless($empleo->restaurante_id === $restaurante->id, 403);

        $validated = $request->validate([
            'titulo'        => 'required|string|max:200',
            'descripcion'   => 'required|string',
            'requisitos'    => 'nullable|string',
            'tipo_contrato' => 'nullable|string|max:50',
            'salario'       => 'nullable|numeric|min:0',
            'fecha_limite'  => 'nullable|date',
            'municipio_id'  => 'required|exists:municipios,id',
        ]);

        // Manejar activo por separado (checkbox)
        $validated['activo'] = $request->boolean('activo', false);

        $empleo->update($validated);

        return redirect()->route('restaurante.empleos.index')
            ->with('success', 'Oferta actualizada correctamente.');
    }

    public function destroy(Empleo $empleo)
    {
        $restaurante = $this->restaurante();
        abort_unless($empleo->restaurante_id === $restaurante->id, 403);

        $empleo->delete();

        return redirect()->route('restaurante.empleos.index')
            ->with('success', 'Oferta eliminada.');
    }
}
