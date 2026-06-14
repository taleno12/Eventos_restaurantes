<?php

namespace App\Http\Controllers\Gastrobar;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastrobarPerfilController extends Controller
{
    public function edit()
    {
        $gastrobar = Auth::user()->gastrobar;

        if (!$gastrobar) {
            return redirect()->route('gastrobar.dashboard')
                ->with('error', 'No tienes un gastrobar asociado.');
        }

        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $gastrobar->departamento_id)->get();

        return view('gastrobar.perfil.edit', compact('gastrobar', 'departamentos', 'municipios'));
    }

    public function update(Request $request)
    {
        $gastrobar = Auth::user()->gastrobar;

        $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'tipo_bar'        => 'nullable|string|max:100',
            'tipo_cocina'     => 'nullable|string|max:100',
            'ambiente'        => 'nullable|string|max:100',
            'capacidad'       => 'nullable|integer|min:1',
            'hora_apertura'   => 'nullable|date_format:H:i',
            'hora_cierre'     => 'nullable|date_format:H:i',
            'dias_atencion'   => 'nullable|array',
            'dias_atencion.*' => 'in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'telefono'        => 'nullable|string|max:50',
            'whatsapp'        => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'instagram'       => 'nullable|url|max:255',
            'facebook'        => 'nullable|url|max:255',
            'tiktok'          => 'nullable|url|max:255',
            'direccion'       => 'nullable|string|max:255',
            'latitud'         => 'nullable|numeric|between:-90,90',
            'longitud'        => 'nullable|numeric|between:-180,180',
            'imagen_principal'=> 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $gastrobar->fill($request->except(['imagen_principal', 'dias_atencion']));
        $gastrobar->dias_atencion = $request->input('dias_atencion') ?? [];

        if ($request->hasFile('imagen_principal')) {
            if ($gastrobar->imagen_principal) {
                Storage::disk('public')->delete($gastrobar->imagen_principal);
            }
            $gastrobar->imagen_principal = $request->file('imagen_principal')
                ->store('gastrobares/principales', 'public');
        }

        $gastrobar->save();

        return redirect()->route('gastrobar.perfil.edit')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
