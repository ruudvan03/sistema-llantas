@extends('layouts.app')

@section('header_title', 'Reportes y Estadísticas')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ periodo: 'hoy' }">

    {{-- Aviso --}}
    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r-xl px-5 py-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-bold text-blue-900">Módulo en construcción</p>
            <p class="text-sm text-blue-700 mt-0.5">Los datos se conectarán con tus ventas e inventario reales. Por ahora todo está en cero.</p>
        </div>
    </div>

    {{-- Barra de periodo + exportar --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Periodo</span>
            <div class="flex items-center bg-gray-100 rounded-xl p-1">
                <button @click="periodo = 'hoy'" :class="periodo === 'hoy' ? 'bg-[#D32030] text-white' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 rounded-lg text-sm font-medium transition">Hoy</button>
                <button @click="periodo = 'semana'" :class="periodo === 'semana' ? 'bg-[#D32030] text-white' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 rounded-lg text-sm font-medium transition">Semana</button>
                <button @click="periodo = 'mes'" :class="periodo === 'mes' ? 'bg-[#D32030] text-white' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 rounded-lg text-sm font-medium transition">Mes</button>
                <button @click="periodo = 'personalizado'" :class="periodo === 'personalizado' ? 'bg-[#D32030] text-white' : 'text-gray-600 hover:text-gray-900'" class="px-4 py-1.5 rounded-lg text-sm font-medium transition">Personalizado</button>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Sucursal</span>
                <select class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#D32030]">
                    <option>Todas las sucursales</option>
                    <option>Administración General</option>
                    <option>Chalco</option>
                </select>
            </div>
            <button class="inline-flex items-center gap-2 px-4 py-2 bg-[#D32030] text-white rounded-xl text-sm font-semibold hover:bg-[#B91C2C] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar
            </button>
        </div>
    </div>

    {{-- Métricas (todo en cero) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase">vs. ayer</span>
            </div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Ingresos Totales</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">$0.00</p>
            <p class="text-xs text-gray-400 font-medium mt-2">→ 0% incremento</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-[#1A1A1A] text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase">vs. ayer</span>
            </div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Ventas Realizadas</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">0</p>
            <p class="text-xs text-gray-400 font-medium mt-2">→ Sin cambios</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-1.13a4 4 0 100-5.4M9 14a4 4 0 100-8 4 4 0 000 8z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-400 uppercase">vs. ayer</span>
            </div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Nuevos Clientes</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">0</p>
            <p class="text-xs text-gray-400 font-medium mt-2">→ 0% tasa</p>
        </div>
    </div>

    {{-- Gráficas (placeholder) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-sm font-bold text-gray-800">Ventas por Día</h3>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
            </div>
            <p class="text-xs text-gray-400 mb-4">Tendencia de ingresos en el tiempo</p>
            <div class="h-56 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-300">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M3 21h18"/></svg>
                <p class="text-sm font-medium">Gráfico de líneas</p>
                <p class="text-xs">Se mostrará cuando haya ventas</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-sm font-bold text-gray-800">Ventas por Sucursal</h3>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/></svg>
            </div>
            <p class="text-xs text-gray-400 mb-4">Desempeño comparativo regional</p>
            <div class="h-56 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-300">
                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6m4 6V9m4 10V5M3 21h18"/></svg>
                <p class="text-sm font-medium">Gráfico de barras</p>
                <p class="text-xs">Esperando registros</p>
            </div>
        </div>
    </div>

    {{-- Productos más vendidos --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Productos más Vendidos</h3>
                <p class="text-xs text-gray-400 mt-0.5">Artículos con mayor rotación en inventario</p>
            </div>
            <a href="{{ route('inventario.index') }}" class="text-xs font-semibold text-[#D32030] hover:text-[#B91C2C] transition">Ver catálogo completo →</a>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-300 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-sm text-gray-400">Aún no hay productos vendidos para mostrar</p>
            </div>
        </div>
    </div>

</div>
@endsection