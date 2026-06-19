<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastrobarNotificacionController extends Controller
{
    public function index()
    {
        $gastrobar = Auth::user()->gastrobar;

        $notificaciones = Notificacion::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        $noLeidas = Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->count();

        return view('gastrobar.notificaciones.index', compact(
            'gastrobar',
            'notificaciones',
            'noLeidas'
        ));
    }

    public function marcarLeida(Notificacion $notificacion)
    {
        abort_unless($notificacion->user_id === Auth::id(), 403);
        $notificacion->update(['leida' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function contarNoLeidas()
    {
        $count = Notificacion::where('user_id', Auth::id())
            ->where('leida', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
