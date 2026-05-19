<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-store text-orange-500"></i>
                    Restaurantes
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fas fa-circle text-orange-400 text-xs mr-1"></i>
                    Gestiona los restaurantes registrados en la plataforma.
                </p>
            </div>
            <a href="{{ route('restaurantes.create') }}"
               class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-all shadow">
                <i class="fas fa-plus"></i> Nuevo Restaurante
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

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                <i class="fas fa-store text-orange-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $restaurantes->total() }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Total Restaurantes</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $activos }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Restaurantes Activos</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalDepartamentos }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Departamentos</p>
            </div>
        </div>
    </div>

    {{-- Tabla de Restaurantes --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Restaurante</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Ubicación Regional</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">WhatsApp / Teléfono</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Correo</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Estado</th>
                    <th class="text-left px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Registrado</th>
                    <th class="text-right px-6 py-4 text-xs font-bold uppercase tracking-widest text-gray-400">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($restaurantes as $restaurante)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($restaurante->nombre, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $restaurante->nombre }}</p>
                                    
                                    {{-- Micro-badges interactivos de Redes Sociales agregados aquí --}}
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($restaurante->facebook)
                                            <a href="{{ $restaurante->facebook }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors" title="Facebook">
                                                <i class="fab fa-facebook text-xs"></i>
                                            </a>
                                        @endif
                                        @if($restaurante->instagram)
                                            <a href="{{ $restaurante->instagram }}" target="_blank" class="text-gray-400 hover:text-pink-600 transition-colors" title="Instagram">
                                                <i class="fab fa-instagram text-xs"></i>
                                            </a>
                                        @endif
                                        @if($restaurante->tiktok)
                                            <a href="{{ $restaurante->tiktok }}" target="_blank" class="text-gray-400 hover:text-black transition-colors" title="TikTok">
                                                <i class="fab fa-tiktok text-xs"></i>
                                            </a>
                                        @endif
                                        @if(!$restaurante->facebook && !$restaurante->instagram && !$restaurante->tiktok)
                                            <span class="text-xs text-gray-400 italic">Sin redes configuradas</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Columna de Ubicación Actualizada con Departamento y Municipio --}}
                        <td class="px-6 py-4">
                            <div class="text-gray-800 font-medium">
                                <i class="fas fa-map-marker-alt text-orange-500 mr-1 text-xs"></i>
                                {{ $restaurante->departamento->nombre ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-400 pl-4 mt-0.5">
                                {{ $restaurante->municipio->nombre ?? 'Sin municipio asignado' }}
                            </div>
                        </td>

                        {{-- Columna de Teléfono actualizada dinámicamente preferenciando WhatsApp --}}
                        <td class="px-6 py-4 text-gray-600">
                            @if($restaurante->whatsapp)
                                <div class="flex items-center gap-1.5 text-gray-800 font-medium">
                                    <i class="fab fa-whatsapp text-green-500 text-sm"></i>
                                    <span>{{ $restaurante->whatsapp }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-xs">No asignado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $restaurante->email ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($restaurante->activo)
                                <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-xs font-semibold px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">
                            {{ $restaurante->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('restaurantes.show', $restaurante) }}"
                                   class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-500 flex items-center justify-center transition-colors"
                                   title="Ver detalle">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('restaurantes.edit', $restaurante) }}"
                                   class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-colors"
                                   title="Editar">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('restaurantes.destroy', $restaurante) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar este restaurante?')">
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
                                <i class="fas fa-store text-4xl opacity-30"></i>
                                <p class="font-semibold">No hay restaurantes registrados</p>
                                <p class="text-sm opacity-70">Crea el primer restaurante con el botón de arriba.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if($restaurantes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $restaurantes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>