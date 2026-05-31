<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight tracking-tight">
            Gestión de Gastrobares
        </h2>
    </x-slot>

    <div class="py-12 animate-fade-in">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- CABECERA --}}
            <div class="flex flex-wrap justify-between items-center mb-6 gap-3">
                <div>
                    <p class="text-gray-500 text-sm">Total: <span class="font-bold text-gray-700">{{ $gastrobares->total() }}</span> gastrobares registrados</p>
                </div>
                <a href="{{ route('admin.gastrobares.create') }}"
                   class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-purple-200 transition-all no-underline">
                    <i class="fas fa-plus"></i> Nuevo Gastrobar
                </a>
            </div>

            {{-- ALERTA ÉXITO --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-bold shadow-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABLA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[1.5rem] border border-gray-100">

                {{-- Header tabla --}}
                <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 p-6">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-500/20 p-2.5 rounded-xl border border-purple-500/30">
                            <i class="fas fa-cocktail text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-bold">Gastrobares Registrados</h3>
                            <p class="text-gray-400 text-xs">Administrá todos los gastrobares de la plataforma</p>
                        </div>
                    </div>
                </div>

                @if($gastrobares->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="bg-purple-50 p-5 rounded-full mb-4">
                            <i class="fas fa-cocktail text-purple-300 text-4xl"></i>
                        </div>
                        <h3 class="text-gray-500 font-bold text-lg">No hay gastrobares registrados</h3>
                        <p class="text-gray-400 text-sm mt-1 mb-4">Comenzá agregando el primero</p>
                        <a href="{{ route('admin.gastrobares.create') }}"
                           class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all no-underline">
                            <i class="fas fa-plus"></i> Nuevo Gastrobar
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50/50">
                                    <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Gastrobar</th>
                                    <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Tipo</th>
                                    <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Ubicación</th>
                                    <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Horario</th>
                                    <th class="text-left px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Estado</th>
                                    <th class="text-center px-6 py-4 text-xs font-black uppercase tracking-widest text-gray-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($gastrobares as $gastrobar)
                                <tr class="hover:bg-gray-50/50 transition-colors">

                                    {{-- Gastrobar --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl overflow-hidden flex-shrink-0 bg-purple-50">
                                                @if($gastrobar->imagen_principal)
                                                    <img src="{{ Storage::url($gastrobar->imagen_principal) }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-cocktail text-purple-300"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $gastrobar->nombre }}</p>
                                                <p class="text-xs text-gray-400">{{ $gastrobar->email ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Tipo --}}
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            @if($gastrobar->tipo_bar)
                                                <span class="inline-block bg-purple-50 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                                    {{ $gastrobar->tipo_bar }}
                                                </span>
                                            @endif
                                            @if($gastrobar->tipo_cocina)
                                                <p class="text-xs text-gray-400">{{ $gastrobar->tipo_cocina }}</p>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Ubicación --}}
                                    <td class="px-6 py-4">
                                        <p class="text-gray-700 font-medium">{{ $gastrobar->departamento->nombre ?? '—' }}</p>
                                        <p class="text-xs text-gray-400">{{ $gastrobar->municipio->nombre ?? '—' }}</p>
                                    </td>

                                    {{-- Horario --}}
                                    <td class="px-6 py-4">
                                        <p class="text-gray-700 text-xs font-medium">{{ $gastrobar->horario_texto }}</p>
                                        @if($gastrobar->ambiente)
                                            <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-lg mt-1">
                                                {{ $gastrobar->ambiente }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4">
                                        @if($gastrobar->activo)
                                            <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-lg">
                                                <i class="fas fa-circle text-[6px]"></i> Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-lg">
                                                <i class="fas fa-circle text-[6px]"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.gastrobares.show', $gastrobar) }}"
                                               class="bg-gray-100 hover:bg-gray-200 text-gray-600 w-8 h-8 rounded-lg flex items-center justify-center transition-colors no-underline"
                                               title="Ver">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.gastrobares.edit', $gastrobar) }}"
                                               class="bg-blue-50 hover:bg-blue-100 text-blue-600 w-8 h-8 rounded-lg flex items-center justify-center transition-colors no-underline"
                                               title="Editar">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.gastrobares.destroy', $gastrobar) }}" method="POST"
                                                  onsubmit="return confirm('¿Seguro que querés eliminar este gastrobar?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="bg-red-50 hover:bg-red-100 text-red-600 w-8 h-8 rounded-lg flex items-center justify-center transition-colors border-0 cursor-pointer"
                                                        title="Eliminar">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($gastrobares->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $gastrobares->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    </style>

</x-app-layout>