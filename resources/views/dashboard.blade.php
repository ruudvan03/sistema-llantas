@extends('layouts.app')

@section('header_title', 'Resumen General')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Buen día, {{ Auth::user()->name ?? 'Administrador' }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ now()->translatedFormat('l, j \d\e F \d\e Y') }}</p>
        </div>
        <a href="{{ route('ventas.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#D32030] text-white rounded-xl text-sm font-semibold hover:bg-[#B91C2C] transition shadow-lg shadow-red-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nueva Venta
        </a>
    </div>

    {{-- Métricas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

        <div class="bg-[#0F0F0F] rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between mb-5">
                <div class="w-11 h-11 rounded-xl bg-[#D32030] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Hoy</span>
            </div>
            <p class="text-xs text-gray-500 font-medium">Ventas de Hoy</p>
            <p class="text-3xl font-bold mt-1">${{ number_format($ventasHoy ?? 0, 2) }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-5">
                <div class="w-11 h-11 rounded-xl bg-[#1A1A1A] text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 font-medium">Llantas Vendidas</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $llantasVendidas ?? 0 }} <span class="text-base font-medium text-gray-400">pzas</span></p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-5">
                <div class="w-11 h-11 rounded-xl bg-red-50 text-[#D32030] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </div>
                @if(($bajoStock ?? 0) > 0)
                    <span class="text-[10px] font-bold text-[#D32030] bg-red-50 border border-red-200 px-2 py-0.5 rounded-full">Atención</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 font-medium">Bajo Stock</p>
            <p class="text-3xl font-bold mt-1 {{ ($bajoStock ?? 0) > 0 ? 'text-[#D32030]' : 'text-gray-900' }}">{{ $bajoStock ?? 0 }} <span class="text-base font-medium text-gray-400">productos</span></p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <div class="flex items-center justify-between mb-5">
                <div class="w-11 h-11 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-1.13a4 4 0 100-5.4M9 14a4 4 0 100-8 4 4 0 000 8z"/></svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 font-medium">Clientes Nuevos</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $clientesNuevos ?? 0 }} <span class="text-base font-medium text-gray-400">hoy</span></p>
        </div>
    </div>

    {{-- Últimas Ventas --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Últimas Ventas</h3>
                <p class="text-xs text-gray-400 mt-0.5">Transacciones recientes</p>
            </div>
            <a href="{{ route('ventas.index') }}" class="text-xs font-semibold text-[#D32030] hover:text-[#B91C2C] transition">Ver todas →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($ultimasVentas ?? [] as $venta)
                <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">#{{ $venta->folio }}</p>
                            <p class="text-xs text-gray-400">{{ $venta->nombre_cliente_temporal ?? 'Mostrador' }} · {{ $venta->created_at->format('H:i') ?? '' }}</p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-gray-800">${{ number_format($venta->total, 2) }}</p>
                </div>
            @empty
                <div class="text-center py-14">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-300 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-sm text-gray-400">Sin ventas registradas hoy</p>
                    <a href="{{ route('ventas.index') }}" class="text-xs text-[#D32030] font-semibold mt-1 inline-block hover:underline">Ir al punto de venta →</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Rendimiento por Sucursal --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Rendimiento por Sucursal</h3>
                <p class="text-xs text-gray-400 mt-0.5">Comparativo de ventas del día</p>
            </div>
            <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-wide">Hoy</span>
        </div>
        <div class="p-6">
            <table class="w-full">
                <thead>
                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                        <th class="pb-4 text-left">Sucursal</th>
                        <th class="pb-4 text-left">Ventas</th>
                        <th class="pb-4 text-left">Llantas</th>
                        <th class="pb-4 text-left">Rendimiento</th>
                        <th class="pb-4 text-left">vs. Ayer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rendimientoSucursales ?? [] as $sucursal)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 pr-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-[#D32030] shrink-0"></div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $sucursal->nombre }}</span>
                                </div>
                            </td>
                            <td class="py-4 pr-4 text-sm font-bold text-gray-800">${{ number_format($sucursal->ventas, 2) }}</td>
                            <td class="py-4 pr-4 text-sm text-gray-500">{{ $sucursal->llantas }} pzas</td>
                            <td class="py-4 pr-4">
                                @php $maxV = collect($rendimientoSucursales)->max('ventas') ?: 1; $pct = round(($sucursal->ventas / $maxV) * 100); @endphp
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-[#D32030]" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-400 w-8">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="py-4">
                                @if($sucursal->variacion >= 0)
                                    <span class="text-sm font-semibold text-emerald-600">+{{ $sucursal->variacion }}%</span>
                                @else
                                    <span class="text-sm font-semibold text-[#D32030]">{{ $sucursal->variacion }}%</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <p class="text-sm text-gray-400">Se mostrarán cuando haya ventas registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection