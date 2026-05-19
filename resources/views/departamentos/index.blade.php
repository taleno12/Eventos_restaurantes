@extends('layouts.app')

@section('content')
{{-- El contenedor principal debe tener padding para no tocar los bordes del sidebar --}}
<div class="p-6 lg:p-8 space-y-8">
    
    {{-- Header unificado con el estilo de tu Dashboard --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Departamentos</h2>
            <p class="text-gray-500 text-sm">Panel de control geográfico y densidad de establecimientos.</p>
        </div>
        <div class="mt-4 md:mt-0">
            {{-- MODIFICADO: Corregida la ruta de 'departamentos.nuevo' a 'departamentos.create' para evitar el error de ruta no definida --}}
            <a href="{{ route('departamentos.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition-all shadow-md active:scale-95">
                <i class="fas fa-plus-circle"></i> Agregar Departamento
            </a>
        </div>
    </div>

    {{-- Buscador estilizado como los inputs del sistema --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="relative group">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
            <input type="text" id="busquedaDepartamento" 
                placeholder="Buscar por nombre o país..." 
                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none">
        </div>
        <div class="flex justify-end items-center">
            <button class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                <i class="fas fa-file-pdf text-red-500"></i> EXPORTAR PDF
            </button>
        </div>
    </div>

    {{-- Tabla envuelta en una tarjeta igual a las de "Resumen General" --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="tablaDepartamentos">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">ID</th>
                        <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider">Información Regional</th>
                        <th class="py-4 px-6 text-[11px] font-bold uppercase text-gray-400 tracking-wider text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($departamentos as $depto)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="text-xs font-semibold text-gray-400">#{{ $depto->id }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">
                                    {{ $depto->nombre }}
                                </span>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400">
                                        <i class="fas fa-flag text-[9px]"></i> NICARAGUA
                                    </span>
                                    
                                    {{-- Enlace de redirección directo con filtro por parámetro de URL --}}
                                    <a href="{{ route('restaurantes.index', ['departamento_id' => $depto->id]) }}" 
                                       class="text-[10px] font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-md flex items-center gap-1 transition-all no-underline border-0">
                                        <i class="fas fa-utensils text-[9px]"></i> 
                                        <span>{{ $depto->restaurantes_count }} Restaurantes inscritos</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('departamentos.edit', $depto->id) }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="{{ route('departamentos.destroy', $depto->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" onclick="return confirm('¿Eliminar?')">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-10 text-center text-gray-400 text-sm italic">No hay datos disponibles.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Scripts de Control --}}
<script>
    // Buscador original perfectamente funcional
    document.getElementById('busquedaDepartamento').addEventListener('input', function() {
        const val = this.value.toLowerCase();
        document.querySelectorAll('#tablaDepartamentos tbody tr').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    });
</script>
@endsection