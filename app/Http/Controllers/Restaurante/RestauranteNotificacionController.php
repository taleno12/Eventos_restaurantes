<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestauranteNotificacionController extends Controller
{
    public function index()
    {
        $restaurante   = Auth::user()->restaurante;
        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        $noLeidas = Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->count();

        return view('restaurante.notificaciones.index', compact(
            'restaurante',
            'notificaciones',
            'noLeidas'
        ));
    }

    // Marcar una como leída
    public function marcarLeida(Notificacion $notificacion)
    {
        abort_unless($notificacion->user_id === Auth::id(), 403);
        $notificacion->update(['leida' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    // Marcar todas como leídas
    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    // Contar no leídas (para el badge del sidebar — vía AJAX)
    public function contarNoLeidas()
    {
        $count = Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
