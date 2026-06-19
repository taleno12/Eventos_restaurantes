<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Empleo;
use App\Models\SolicitudEmpleo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GastrobarSolicitudController extends Controller
{
    private function gastrobar()
    {
        return Auth::user()->gastrobar;
    }

    public function index(Empleo $empleo)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($empleo->gastrobar_id === $gastrobar->id, 403);

        $solicitudes = $empleo->solicitudes()->latest()->get();

        $empleo->solicitudes()->where('estado', 'nueva')->update(['estado' => 'vista']);

        return view('gastrobar.empleos.solicitudes', compact('gastrobar', 'empleo', 'solicitudes'));
    }

    public function updateEstado(Request $request, SolicitudEmpleo $solicitud)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($solicitud->empleo->gastrobar_id === $gastrobar->id, 403);

        $request->validate([
            'estado' => 'required|in:nueva,vista,contactado,descartado',
        ]);

        $solicitud->update(['estado' => $request->estado]);

        if ($request->ajax()) {
            return response()->json(['ok' => true, 'estado' => $solicitud->estado]);
        }

        return back()->with('success', 'Estado actualizado.');
    }

    public function destroy(SolicitudEmpleo $solicitud)
    {
        $gastrobar = $this->gastrobar();
        abort_unless($solicitud->empleo->gastrobar_id === $gastrobar->id, 403);

        if ($solicitud->curriculum) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($solicitud->curriculum);
        }

        $solicitud->delete();

        return back()->with('success', 'Solicitud eliminada.');
    }
}
