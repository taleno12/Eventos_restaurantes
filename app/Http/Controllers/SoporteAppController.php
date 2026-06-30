<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;

class SoporteAppController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->input('estado');

        $query = Reporte::with('user')->latest();

        if ($estado) {
            $query->where('estado', $estado);
        }

        $reportes = $query->paginate(15)->withQueryString();

        $totalNuevos     = Reporte::where('estado', 'nuevo')->count();
        $totalEnRevision = Reporte::where('estado', 'en_revision')->count();
        $totalResueltos  = Reporte::where('estado', 'resuelto')->count();
        $totalReportes   = Reporte::count();

        return view('soporte.index', compact(
            'reportes',
            'totalNuevos',
            'totalEnRevision',
            'totalResueltos',
            'totalReportes',
            'estado'
        ));
    }

    public function updateEstado(Request $request, Reporte $reporte)
    {
        $request->validate([
            'estado' => 'required|in:nuevo,en_revision,resuelto',
        ]);

        $reporte->update(['estado' => $request->estado]);

        return back()->with('success', 'Estado del reporte actualizado.');
    }
}
