@extends('layouts.app')

@section('header_title', 'Historial de Movimientos')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Historial de Movimientos</h2>
            <p class="text-sm text-gray-500 mt-1">Registro de entradas y salidas del inventario.</p>
        </div>
        <a href="{{ route('inventario.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Volver a Inventario
        </a>
    </div>

    {{-- Tarjetas de resumen --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Entradas</p>
                <p class="text-2xl font-bold text-emerald-700">+{{ number_format($totalEntradas) }}</p>
                <p class="text-[11px] text-gray-400">piezas ingresadas</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-[#D32030] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Salidas</p>
                <p class="text-2xl font-bold text-[#D32030]">-{{ number_format($totalSalidas) }}</p>
                <p class="text-[11px] text-gray-400">piezas retiradas</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Movimientos</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalMovimientos) }}</p>
                <p class="text-[11px] text-gray-400">en total (filtrados)</p>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('inventario.historial') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Búsqueda por producto --}}
            <div class="lg:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Buscar producto</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Marca, medida..." class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 outline-none focus:ring-2 focus:ring-[#D32030]">
            </div>
            {{-- Tipo --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tipo</label>
                <select name="tipo" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 outline-none focus:ring-2 focus:ring-[#D32030]">
                    <option value="">Todos</option>
                    <option value="entrada" {{ request('tipo') == 'entrada' ? 'selected' : '' }}>Entradas</option>
                    <option value="salida" {{ request('tipo') == 'salida' ? 'selected' : '' }}>Salidas</option>
                </select>
            </div>
            {{-- Motivo --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Motivo</label>
                <select name="motivo" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 outline-none focus:ring-2 focus:ring-[#D32030] capitalize">
                    <option value="">Todos los motivos</option>
                    @foreach($motivosDisponibles as $mot)
                        <option value="{{ $mot }}" {{ request('motivo') == $mot ? 'selected' : '' }}>{{ ucfirst($mot) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Sucursal (solo admin) --}}
            @if($esAdmin)
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sucursal</label>
                    <select name="sucursal_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 outline-none focus:ring-2 focus:ring-[#D32030]">
                        <option value="">Todas las sucursales</option>
                        @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}" {{ request('sucursal_id') == $suc->id ? 'selected' : '' }}>{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            {{-- Fecha desde --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Desde</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 outline-none focus:ring-2 focus:ring-[#D32030]">
            </div>
            {{-- Fecha hasta --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hasta</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg block w-full p-2.5 outline-none focus:ring-2 focus:ring-[#D32030]">
            </div>
        </div>

        <div class="flex items-center gap-2 pt-1">
            <button type="submit" class="px-5 py-2 bg-[#D32030] text-white font-semibold rounded-lg text-sm hover:bg-[#B91C2C] transition">Aplicar filtros</button>
            @if(request()->hasAny(['tipo', 'motivo', 'q', 'fecha_desde', 'fecha_hasta', 'sucursal_id']))
                <a href="{{ route('inventario.historial') }}" class="px-5 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Limpiar</a>
            @endif
        </div>
    </form>

    {{-- Tabla de movimientos --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-semibold whitespace-nowrap">Fecha</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Tipo</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Producto</th>
                        <th class="p-4 font-semibold text-center whitespace-nowrap">Cantidad</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Motivo</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Sucursal</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse($movimientos as $mov)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="p-4 whitespace-nowrap text-gray-600">
                                {{ \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y') }}
                                <span class="text-xs text-gray-400 block">{{ \Carbon\Carbon::parse($mov->fecha)->format('H:i') }}</span>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($mov->tipo === 'entrada')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Entrada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-red-50 text-[#D32030] border border-red-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        Salida
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-gray-800">{{ $mov->producto->marca ?? 'Producto eliminado' }}</p>
                                <p class="text-xs text-gray-400">{{ $mov->producto->medida ?? '' }}</p>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <span class="font-bold {{ $mov->tipo === 'entrada' ? 'text-emerald-700' : 'text-[#D32030]' }}">
                                    {{ $mov->tipo === 'entrada' ? '+' : '-' }}{{ $mov->cantidad }}
                                </span>
                            </td>
                            <td class="p-4 whitespace-nowrap capitalize text-gray-600">{{ $mov->motivo ?? '—' }}</td>
                            <td class="p-4 whitespace-nowrap text-gray-600">{{ $mov->sucursal->nombre ?? 'N/D' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400">
                                <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-300 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                <p class="text-sm">No hay movimientos con esos filtros.</p>
                                <p class="text-xs mt-1">Prueba limpiar los filtros o registra una entrada/salida.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movimientos->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $movimientos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection