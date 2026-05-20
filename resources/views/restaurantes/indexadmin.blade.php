{{-- resources/views/restaurantes/indexadmin.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- ── Encabezado ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
                <div class="w-9 h-9 bg-orange-600 rounded-xl flex items-center justify-center shadow-md shadow-orange-600/30">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                Restaurantes
            </h1>
            <p class="text-slate-400 text-sm mt-1 ml-12">Gestión de establecimientos registrados en la plataforma.</p>
        </div>

        <a href="{{ route('restaurantes.create') }}"
           class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all shadow-md shadow-orange-600/20 no-underline shrink-0">
            <i class="fas fa-plus text-xs"></i> Agregar Restaurante
        </a>
    </div>

    {{-- ── Alertas ── --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- ── Métricas rápidas ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-orange-600/15 rounded-xl flex items-center justify-center text-orange-500">
                <i class="fas fa-store text-lg"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Total</p>
                <p class="text-white text-2xl font-bold leading-none mt-0.5">{{ $restaurantes->total() }}</p>
            </div>
        </div>
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-500/15 rounded-xl flex items-center justify-center text-emerald-400">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Activos</p>
                <p class="text-white text-2xl font-bold leading-none mt-0.5">{{ $activos }}</p>
            </div>
        </div>
        <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-blue-500/15 rounded-xl flex items-center justify-center text-blue-400">
                <i class="fas fa-map text-lg"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Departamentos</p>
                <p class="text-white text-2xl font-bold leading-none mt-0.5">{{ $totalDepartamentos }}</p>
            </div>
        </div>
    </div>

    {{-- ── Filtro rápido ── --}}
    <form method="GET" action="{{ route('admin.restaurantes.index') }}"
          class="bg-slate-800/60 border border-slate-700/50 rounded-2xl p-4 flex flex-wrap gap-3 items-center">
        <div class="relative flex-1 min-w-[180px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs pointer-events-none"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar por nombre..."
                   class="w-full bg-slate-900/60 border border-slate-600/50 text-white text-sm rounded-xl pl-9 pr-4 py-2.5 outline-none focus:border-orange-500 placeholder-slate-500 transition-colors">
        </div>
        <div class="relative min-w-[160px]">
            <i class="fas fa-map absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-xs pointer-events-none z-10"></i>
            <select name="departamento_id"
                    class="w-full bg-slate-900/60 border border-slate-600/50 text-white text-sm rounded-xl pl-9 pr-4 py-2.5 outline-none focus:border-orange-500 appearance-none cursor-pointer transition-colors">
                <option value="">Todos los departamentos</option>
                @foreach($departamentos as $depto)
                    <option value="{{ $depto->id }}" {{ request('departamento_id') == $depto->id ? 'selected' : '' }}>
                        {{ $depto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                class="bg-orange-600 hover:bg-orange-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all border-0 cursor-pointer flex items-center gap-2">
            <i class="fas fa-filter text-xs"></i> Filtrar
        </button>
        @if(request('search') || request('departamento_id'))
            <a href="{{ route('admin.restaurantes.index') }}"
               class="text-slate-400 hover:text-red-400 text-sm transition-colors no-underline flex items-center gap-1">
                <i class="fas fa-times text-xs"></i> Limpiar
            </a>
        @endif
    </form>

    {{-- ── Tabla ── --}}
    <div class="bg-slate-800/60 border border-slate-700/50 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-700/50">
                        <th class="text-left text-slate-400 text-xs uppercase tracking-wider font-semibold px-5 py-4">Restaurante</th>
                        <th class="text-left text-slate-400 text-xs uppercase tracking-wider font-semibold px-5 py-4 hidden md:table-cell">Especialidad</th>
                        <th class="text-left text-slate-400 text-xs uppercase tracking-wider font-semibold px-5 py-4 hidden lg:table-cell">Ubicación</th>
                        <th class="text-left text-slate-400 text-xs uppercase tracking-wider font-semibold px-5 py-4 hidden lg:table-cell">Contacto</th>
                        <th class="text-left text-slate-400 text-xs uppercase tracking-wider font-semibold px-5 py-4 hidden sm:table-cell">Estado</th>
                        <th class="text-right text-slate-400 text-xs uppercase tracking-wider font-semibold px-5 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/30">
                    @forelse($restaurantes as $restaurante)
                        <tr class="hover:bg-slate-700/20 transition-colors group">

                            {{-- Nombre + imagen --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-700 shrink-0 flex items-center justify-center">
                                        @if($restaurante->imagen)
                                            <img src="{{ asset('storage/' . $restaurante->imagen) }}"
                                                 alt="{{ $restaurante->nombre }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-utensils text-slate-500 text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold leading-tight">{{ $restaurante->nombre }}</p>
                                        <p class="text-slate-500 text-xs mt-0.5">ID #{{ $restaurante->id }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Especialidad --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                @if($restaurante->especialidad)
                                    <span class="bg-orange-600/15 text-orange-400 text-[11px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide">
                                        {{ $restaurante->especialidad }}
                                    </span>
                                @else
                                    <span class="text-slate-600 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Ubicación --}}
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <div class="flex flex-col gap-0.5">
                                    @if($restaurante->departamento)
                                        <span class="text-slate-300 text-xs flex items-center gap-1.5">
                                            <i class="fas fa-map-marker-alt text-orange-500 text-[10px]"></i>
                                            {{ $restaurante->departamento->nombre }}
                                        </span>
                                    @endif
                                    @if($restaurante->municipio)
                                        <span class="text-slate-500 text-xs flex items-center gap-1.5">
                                            <i class="fas fa-city text-slate-600 text-[10px]"></i>
                                            {{ $restaurante->municipio->nombre }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Contacto --}}
                            <td class="px-5 py-4 hidden lg:table-cell">
                                <div class="flex flex-col gap-0.5">
                                    @if($restaurante->email)
                                        <span class="text-slate-400 text-xs flex items-center gap-1.5">
                                            <i class="fas fa-envelope text-slate-600 text-[10px]"></i>
                                            {{ Str::limit($restaurante->email, 22) }}
                                        </span>
                                    @endif
                                    @if($restaurante->whatsapp)
                                        <span class="text-slate-400 text-xs flex items-center gap-1.5">
                                            <i class="fab fa-whatsapp text-emerald-600 text-[10px]"></i>
                                            {{ $restaurante->whatsapp }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4 hidden sm:table-cell">
                                @if($restaurante->activo ?? true)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400 text-[11px] font-semibold px-3 py-1 rounded-full border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-700/50 text-slate-400 text-[11px] font-semibold px-3 py-1 rounded-full border border-slate-600/30">
                                        <span class="w-1.5 h-1.5 bg-slate-500 rounded-full"></span> Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('restaurantes.show', $restaurante) }}"
                                       title="Ver detalle"
                                       class="w-8 h-8 rounded-lg bg-slate-700/50 hover:bg-blue-600/20 border border-slate-600/30 hover:border-blue-500/30 flex items-center justify-center text-slate-400 hover:text-blue-400 transition-all no-underline">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('restaurantes.edit', $restaurante) }}"
                                       title="Editar"
                                       class="w-8 h-8 rounded-lg bg-slate-700/50 hover:bg-orange-600/20 border border-slate-600/30 hover:border-orange-500/30 flex items-center justify-center text-slate-400 hover:text-orange-400 transition-all no-underline">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('restaurantes.destroy', $restaurante) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar {{ addslashes($restaurante->nombre) }}? Esta acción no se puede deshacer.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Eliminar"
                                                class="w-8 h-8 rounded-lg bg-slate-700/50 hover:bg-red-600/20 border border-slate-600/30 hover:border-red-500/30 flex items-center justify-center text-slate-400 hover:text-red-400 transition-all cursor-pointer border-0">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-4 text-slate-500">
                                    <div class="w-16 h-16 bg-slate-700/40 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-store text-2xl text-slate-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold mb-1">Sin restaurantes registrados</p>
                                        <p class="text-sm">Agrega el primer establecimiento para comenzar.</p>
                                    </div>
                                    <a href="{{ route('restaurantes.create') }}"
                                       class="bg-orange-600 hover:bg-orange-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all no-underline flex items-center gap-2 mt-2">
                                        <i class="fas fa-plus text-xs"></i> Agregar Restaurante
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($restaurantes->hasPages())
            <div class="px-5 py-4 border-t border-slate-700/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-slate-500 text-xs">
                    Mostrando {{ $restaurantes->firstItem() }}–{{ $restaurantes->lastItem() }}
                    de {{ $restaurantes->total() }} restaurantes
                </p>
                <div class="pagination-dark">
                    {{ $restaurantes->withQueryString()->links() }}
                </div>
            </div>
        @else
            <div class="px-5 py-3 border-t border-slate-700/50">
                <p class="text-slate-500 text-xs">{{ $restaurantes->total() }} restaurante{{ $restaurantes->total() != 1 ? 's' : '' }} en total</p>
            </div>
        @endif
    </div>

</div>

<style>
    .pagination-dark nav span,
    .pagination-dark nav a {
        background: transparent !important;
        border-color: rgb(71 85 105 / 0.5) !important;
        color: #94a3b8 !important;
        font-size: 0.75rem;
        border-radius: 0.5rem !important;
    }
    .pagination-dark nav a:hover {
        background: rgb(234 88 12 / 0.15) !important;
        border-color: rgb(234 88 12 / 0.3) !important;
        color: #f97316 !important;
    }
    .pagination-dark nav span[aria-current="page"] span {
        background: #ea580c !important;
        border-color: #ea580c !important;
        color: #fff !important;
    }
</style>
@endsection