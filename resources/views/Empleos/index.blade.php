<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-briefcase text-blue-500"></i>
                    Ofertas de Empleo
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fas fa-circle text-blue-400 text-xs mr-1"></i>
                    Gestiona las vacantes publicadas por los restaurantes.
                </p>
            </div>
            <a href="{{ route('admin.empleos.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-all shadow">
                <i class="fas fa-plus"></i> Nueva Oferta
            </a>
        </div>
    </x-slot>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl text-sm">
            <i class="fas fa-check-circle text-green-500"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-briefcase text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $empleos->total() }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Total Ofertas</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $activas }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Vacantes Activas</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                <i class="fas fa-store text-orange-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $restaurantesConOfertas }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Restaurantes con Ofertas</p>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Puesto</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Restaurante</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Tipo</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Salario</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Estado</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Publicado</th>
                    <th class="text-right px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($empleos as $empleo)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($empleo->titulo, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $empleo->titulo }}</p>
                                    <p class="text-xs text-gray-400">ID: #{{ $empleo->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <i class="fas fa-store text-gray-300 mr-1"></i>
                            {{ $empleo->restaurante->nombre ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                                <i class="fas fa-clock text-[10px]"></i>
                                {{ $empleo->tipo_contrato ?? 'No especificado' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ $empleo->salario ? 'C$ ' . number_format($empleo->salario, 0) : 'A convenir' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($empleo->activo)
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Activa
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs font-semibold px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactiva
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $empleo->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.empleos.edit', $empleo) }}"
                                   class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                   title="Editar">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.empleos.destroy', $empleo) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta oferta?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors"
                                            title="Eliminar">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i class="fas fa-briefcase text-4xl opacity-30"></i>
                                <p class="font-semibold">No hay ofertas registradas</p>
                                <p class="text-sm opacity-70">Crea la primera oferta con el botón de arriba.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($empleos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $empleos->links() }}
            </div>
        @endif
    </div>
</x-app-layout>