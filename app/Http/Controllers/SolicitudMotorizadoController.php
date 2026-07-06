<?php

namespace App\Http\Controllers;

use App\Models\SolicitudMotorizado;
use Illuminate\Http\Request;

class SolicitudMotorizadoController extends Controller
{
    public function index()
    {
        $solicitudes = SolicitudMotorizado::with(['user', 'departamento', 'municipio'])
            ->orderByRaw("CASE WHEN estado = 'pendiente' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);

        $pendientesCount = SolicitudMotorizado::where('estado', 'pendiente')->count();

        return view('solicitudes-motorizado.index', compact('solicitudes', 'pendientesCount'));
    }

    public function show(SolicitudMotorizado $solicitud)
    {
        $solicitud->load(['user', 'departamento', 'municipio']);

        return view('solicitudes-motorizado.show', compact('solicitud'));
    }

    public function aprobar(Request $request, SolicitudMotorizado $solicitud)
    {
        $solicitud->update([
            'estado'      => 'aprobada',
            'revisado_por'=> auth()->id(),
            'revisado_at' => now(),
        ]);

        $solicitud->user->update([
            'role'     => 'motorizado',
            'vehiculo' => $solicitud->tipo_vehiculo,
            'placa'    => $solicitud->placa,
        ]);

        return redirect()
            ->route('admin.solicitudes-motorizado.index')
            ->with('success', "Solicitud aprobada. {$solicitud->user->name} ya es motorizado.");
    }

    public function rechazar(Request $request, SolicitudMotorizado $solicitud)
    {
        $request->validate([
            'motivo_rechazo' => 'nullable|string|max:500',
        ]);

        $solicitud->update([
            'estado'         => 'rechazada',
            'motivo_rechazo' => $request->motivo_rechazo,
            'revisado_por'   => auth()->id(),
            'revisado_at'    => now(),
        ]);

        return redirect()
            ->route('admin.solicitudes-motorizado.index')
            ->with('success', 'Solicitud rechazada.');
    }
}
