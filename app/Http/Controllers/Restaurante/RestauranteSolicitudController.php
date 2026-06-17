<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Empleo;
use App\Models\SolicitudEmpleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestauranteSolicitudController extends Controller
{
    private function restaurante()
    {
        return Auth::user()->restaurante;
    }

    /**
     * Listado de solicitudes de un empleo específico
     */
    public function index(Empleo $empleo)
    {
        $restaurante = $this->restaurante();
        abort_unless($empleo->restaurante_id === $restaurante->id, 403);

        $solicitudes = $empleo->solicitudes()->latest()->get();

        // Marcar nuevas como vistas automáticamente al abrir
        $empleo->solicitudes()->where('estado', 'nueva')->update(['estado' => 'vista']);

        return view('restaurante.empleos.solicitudes', compact('restaurante', 'empleo', 'solicitudes'));
    }

    /**
     * Cambiar estado de una solicitud (AJAX o redirect)
     */
    public function updateEstado(Request $request, SolicitudEmpleo $solicitud)
    {
        $restaurante = $this->restaurante();

        // Verificar que la solicitud pertenece a un empleo de este restaurante
        abort_unless($solicitud->empleo->restaurante_id === $restaurante->id, 403);

        $request->validate([
            'estado' => 'required|in:nueva,vista,contactado,descartado',
        ]);

        $solicitud->update(['estado' => $request->estado]);

        if ($request->ajax()) {
            return response()->json(['ok' => true, 'estado' => $solicitud->estado]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    /**
     * Eliminar solicitud
     */
    public function destroy(SolicitudEmpleo $solicitud)
    {
        $restaurante = $this->restaurante();
        abort_unless($solicitud->empleo->restaurante_id === $restaurante->id, 403);

        if ($solicitud->curriculum) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($solicitud->curriculum);
        }

        $solicitud->delete();

        return back()->with('success', 'Solicitud eliminada.');
    }
}
