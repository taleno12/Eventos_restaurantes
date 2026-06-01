<?php

namespace App\Http\Controllers\Restaurante;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantePerfilController extends Controller
{
    public function edit()
    {
        $restaurante = Auth::user()->restaurante;

        if (!$restaurante) {
            return redirect()->route('restaurante.dashboard')
                ->with('error', 'No tienes un restaurante asociado.');
        }

        $departamentos = Departamento::orderBy('nombre')->get();
        $municipios    = Municipio::where('departamento_id', $restaurante->departamento_id)->get();

        return view('restaurante.perfil.edit', compact('restaurante', 'departamentos', 'municipios'));
    }

    public function update(Request $request)
    {
        $restaurante = Auth::user()->restaurante;

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'especialidad' => 'required|string|max:100',
            'telefono'     => 'nullable|string|max:50',
            'whatsapp'     => 'nullable|string|max:50',
            'instagram'    => 'nullable|url|max:255',
            'facebook'     => 'nullable|url|max:255',
            'tiktok'       => 'nullable|url|max:255',
            'direccion'    => 'nullable|string|max:255',
            'latitud'      => 'nullable|numeric|between:-90,90',
            'longitud'     => 'nullable|numeric|between:-180,180',
            'foto_portada' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $restaurante->fill($request->except('foto_portada'));

        if ($request->hasFile('foto_portada')) {
            if ($restaurante->foto_portada) {
                Storage::disk('public')->delete($restaurante->foto_portada);
            }
            $restaurante->foto_portada = $request->file('foto_portada')
                ->store('restaurantes/principales', 'public');
        }

        $restaurante->save();

        return redirect()->route('restaurante.perfil.edit')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
