@extends('layouts.app')

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
<div class="animate-fade-in" style="font-family: 'Segoe UI', Roboto, sans-serif;">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2 flex-wrap">
                <span class="px-3 py-1 bg-indigo-600 text-[10px] font-black text-white rounded-full uppercase tracking-widest shadow-sm">v2.0 PRO</span>
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.3em]">Sistema de Control Integral</h2>
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-none">Panel Central</h1>
            <p class="text-slate-500 font-medium mt-2 text-sm sm:text-base">Analíticas de rendimiento para <span class="text-indigo-600 font-bold">Gastro.ni</span></p>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 self-start">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner shrink-0">
                <i class="fas fa-bolt"></i>
            </div>
            <div>
                <span class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Estado Global</span>
                <span class="text-sm font-bold text-emerald-500 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sistema Activo
                </span>
            </div>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-12">

        <div class="group bg-white rounded-3xl border border-slate-100 p-5 sm:p-6 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300 flex flex-col justify-between min-h-[150px]">
            <div class="flex justify-between items-start">
                <div class="space-y-1.5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Unidades de Negocio</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ $totalRestaurantes }} <span class="text-sm font-semibold text-slate-400">Locales</span>
                    </h3>
                </div>
                <div class="bg-slate-50 p-3 sm:p-4 rounded-2xl text-slate-700 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-inner shrink-0">
                    <i class="fas fa-store text-lg sm:text-xl"></i>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-emerald-500 font-bold bg-emerald-50/60 px-2.5 py-0.5 rounded-full"><i class="fas fa-caret-up me-1"></i>+12%</span>
                    <span class="text-slate-400 font-medium">Rendimiento mensual</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full w-2/3 group-hover:w-full transition-all duration-700 ease-out"></div>
                </div>
            </div>
        </div>

        <div class="group bg-white rounded-3xl border border-slate-100 p-5 sm:p-6 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-300 flex flex-col justify-between min-h-[150px]">
            <div class="flex justify-between items-start">
                <div class="space-y-1.5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Actividad Reciente</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ $totalEventos }} <span class="text-sm font-semibold text-slate-400">Publicaciones</span>
                    </h3>
                </div>
                <div class="bg-slate-50 p-3 sm:p-4 rounded-2xl text-slate-700 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-inner shrink-0">
                    <i class="fas fa-calendar-check text-lg sm:text-xl"></i>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-blue-500 font-bold bg-blue-50/60 px-2.5 py-0.5 rounded-full">Al día</span>
                    <span class="text-slate-400 font-medium">Actualizado hoy</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full w-1/2 group-hover:w-3/4 transition-all duration-700 ease-out"></div>
                </div>
            </div>
        </div>

        <div class="group bg-white rounded-3xl border border-slate-100 p-5 sm:p-6 shadow-sm hover:shadow-md hover:border-amber-100 transition-all duration-300 flex flex-col justify-between min-h-[150px] sm:col-span-2 lg:col-span-1">
            <div class="flex justify-between items-start">
                <div class="space-y-1.5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">Cobertura Nacional</p>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">
                        {{ $totalDepartamentos }} <span class="text-sm font-semibold text-slate-400">Regiones</span>
                    </h3>
                </div>
                <div class="bg-slate-50 p-3 sm:p-4 rounded-2xl text-slate-700 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-inner shrink-0">
                    <i class="fas fa-map-marked-alt text-lg sm:text-xl"></i>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-amber-600 font-bold bg-amber-50/60 px-2.5 py-0.5 rounded-full">Regional</span>
                    <span class="text-slate-400 font-medium">Departamentos activos</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full w-4/5 group-hover:w-full transition-all duration-700 ease-out"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- Banner Acciones --}}
    <div class="relative group">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-slate-800 rounded-3xl blur-xl opacity-10 group-hover:opacity-20 transition-opacity duration-500"></div>
        <div class="relative bg-slate-900 rounded-3xl p-6 sm:p-10 overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">

            <div class="absolute top-0 right-0 p-4 pointer-events-none opacity-5">
                <i class="fas fa-rocket text-[8rem] sm:text-[12rem] -rotate-12 translate-x-10 translate-y-10 text-white"></i>
            </div>

            <div class="relative z-10">
                <h4 class="text-xl sm:text-2xl font-black text-white tracking-tight">Centro de Operaciones</h4>
                <p class="text-slate-400 text-sm mt-1 max-w-md font-medium">"La excelencia no es un acto, sino un hábito." Gestiona tu red con precisión.</p>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('admin.restaurantes.create') }}"
                   class="inline-flex items-center justify-center px-5 py-3.5 bg-white text-slate-900 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all shadow-md active:scale-95 whitespace-nowrap no-underline">
                    <i class="fas fa-plus me-2"></i> Nuevo Local
                </a>
                <a href="{{ route('eventos.create') }}"
                   class="inline-flex items-center justify-center px-5 py-3.5 bg-slate-800 text-white border border-slate-700 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-700 transition-all active:scale-95 whitespace-nowrap no-underline">
                    <i class="fas fa-bullhorn me-2"></i> Publicar Evento
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection