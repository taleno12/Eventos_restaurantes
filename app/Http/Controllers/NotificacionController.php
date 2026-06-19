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
        $adminId = \Illuminate\Support\Facades\Auth::id();

        $query = Notificacion::query()
            ->with(['contrato', 'pago'])
            ->where('user_id', $adminId)
            ->latest('created_at');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('leida', $request->estado === 'leidas');
        }

        $notificaciones = $query->paginate(15)->withQueryString();

        $totalNoLeidas = Notificacion::noLeidas()
            ->where('user_id', $adminId)
            ->count();

        return view('notificaciones.index', compact('notificaciones', 'totalNoLeidas'));
    }

    /**
     * Guarda una notificación manual enviada desde el panel admin
     * hacia el dueño de un restaurante.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipo'    => 'required|in:contrato_por_vencer,contrato_vencido,pago_pendiente,mensaje_admin',
            'titulo'  => 'required|string|max:255',
            'mensaje' => 'required|string',
            'url'     => 'nullable|string|max:255',
        ]);

        Notificacion::create([
            'user_id' => $request->user_id,
            'tipo'    => $request->tipo,
            'titulo'  => $request->titulo,
            'mensaje' => $request->mensaje,
            'url'     => $request->url,
            'leida'   => false,
        ]);

        return back()->with('success', 'Notificación enviada al restaurante.');
    }

    /**
     * Marca una notificación como leída.
     */
    public function marcarLeida(Notificacion $notificacion): RedirectResponse
    {
        abort_unless($notificacion->user_id === \Illuminate\Support\Facades\Auth::id(), 403);

        $notificacion->update(['leida' => true]);

        return back()->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Marca todas las notificaciones como leídas (solo las del admin actual).
     */
    public function marcarTodasLeidas(): RedirectResponse
    {
        Notificacion::noLeidas()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }

    /**
     * Elimina una notificación.
     */
    public function destroy(Notificacion $notificacion): RedirectResponse
    {
        abort_unless($notificacion->user_id === \Illuminate\Support\Facades\Auth::id(), 403);

        $notificacion->delete();

        return back()->with('success', 'Notificación eliminada.');
    }
}
