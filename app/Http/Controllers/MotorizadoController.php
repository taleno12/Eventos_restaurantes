<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MotorizadoController extends Controller
{
    /**
     * Devuelve los motorizados disponibles cerca del negocio del usuario autenticado
     * (restaurante o gastrobar). Radio configurable via query param ?radio=5
     */
    public function disponiblesCerca(Request $request): JsonResponse
    {
        $user = $request->user();

        // Determinar el negocio del usuario (restaurante o gastrobar) y su ubicacion
        $negocio = null;
        if ($user->isRestaurante() && $user->restaurante) {
            $negocio = $user->restaurante;
        } elseif ($user->isGastrobar() && $user->gastrobar) {
            $negocio = $user->gastrobar;
        }

        if (!$negocio || !$negocio->latitud || !$negocio->longitud) {
            return response()->json([
                'message' => 'Este negocio no tiene una ubicacion registrada todavia.',
            ], 422);
        }

        $radioKm = (float) $request->query('radio', 5);

        $motorizados = User::motorizadosCerca(
            (float) $negocio->latitud,
            (float) $negocio->longitud,
            $radioKm
        )->get(['id', 'name', 'telefono', 'vehiculo', 'placa', 'avatar', 'lat', 'lng']);

        return response()->json([
            'negocio' => [
                'lat' => $negocio->latitud,
                'lng' => $negocio->longitud,
            ],
            'radio_km' => $radioKm,
            'motorizados' => $motorizados,
        ]);
    }

    /**
     * Panel admin: listado de motorizados con boton activar/desactivar.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'motorizado')->latest();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(fn($q) => $q->where('name', 'like', "%$b%")
                                       ->orWhere('telefono', 'like', "%$b%"));
        }

        $motorizados = $query->paginate(15);

        return view('motorizados.index', compact('motorizados'));
    }
}
