<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    /**
     * Lista de notificaciones con filtros simples.
     */
    public function index(Request $request): View
    {
        $query = Notificacion::query()->with(['contrato', 'pago'])->latest('fecha_evento');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('leida', $request->estado === 'leidas');
        }

        $notificaciones = $query->paginate(15)->withQueryString();

        $totalNoLeidas = Notificacion::noLeidas()->count();

        return view('notificaciones.index', compact('notificaciones', 'totalNoLeidas'));
    }

    /**
     * Marca una notificación como leída.
     */
    public function marcarLeida(Notificacion $notificacion): RedirectResponse
    {
        $notificacion->update(['leida' => true]);

        return back()->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Marca todas las notificaciones como leídas.
     */
    public function marcarTodasLeidas(): RedirectResponse
    {
        Notificacion::noLeidas()->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }

    /**
     * Elimina una notificación.
     */
    public function destroy(Notificacion $notificacion): RedirectResponse
    {
        $notificacion->delete();

        return back()->with('success', 'Notificación eliminada.');
    }
}
