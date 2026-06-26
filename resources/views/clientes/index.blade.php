@extends('layouts.app')

@section('header_title', 'Clientes')

@section('content')
@php
    // Datos de ejemplo solo para ver el diseño (no vienen de la base de datos)
    $clientesDemo = [
        ['id' => 23490, 'nombre' => 'Alejandro López', 'telefono' => '55 6123 4567', 'email' => 'a.lopez@email.com', 'rfc' => 'LOAL900512ABC', 'compras' => 14502.00, 'ultima' => '12 Oct 2026', 'vip' => true],
        ['id' => 23491, 'nombre' => 'Beatriz Ruiz', 'telefono' => '55 6991 2345', 'email' => 'beatriz.r@gmail.com', 'rfc' => 'RUBE880301XYZ', 'compras' => 4200.00, 'ultima' => '05 Oct 2026', 'vip' => false],
        ['id' => 23492, 'nombre' => 'Carlos Fernández', 'telefono' => '55 6558 8899', 'email' => 'carlos.fer@outlook.com', 'rfc' => 'FECA751120JKL', 'compras' => 28905.50, 'ultima' => '28 Sep 2026', 'vip' => true],
        ['id' => 23493, 'nombre' => 'Daniela Vega', 'telefono' => '55 6600 1122', 'email' => 'dani.vega@pro-auto.es', 'rfc' => null, 'compras' => 1250.00, 'ultima' => '22 Sep 2026', 'vip' => false],
        ['id' => 23494, 'nombre' => 'Mostrador General', 'telefono' => '—', 'email' => '—', 'rfc' => null, 'compras' => 0, 'ultima' => '—', 'vip' => false],
    ];
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Base de Datos de Clientes</h2>
            <p class="text-sm text-gray-400 mt-1">Gestiona y consulta el historial de tus clientes registrados.</p>
        </div>
        <a href="#" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#D32030] text-white rounded-xl text-sm font-semibold hover:bg-[#B91C2C] transition shadow-lg shadow-red-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo cliente
        </a>
    </div>

    {{-- Métricas (ejemplo) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Clientes</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">1,284</p>
            <p class="text-xs text-emerald-600 font-semibold mt-2">↑ +12% este mes</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Ticket Promedio</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">$342.50</p>
            <p class="text-xs text-emerald-600 font-semibold mt-2">↑ +5% vs año ant.</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Retención</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">78%</p>
            <p class="text-xs text-[#D32030] font-semibold mt-2">↓ -2% esta semana</p>
        </div>
        <div class="bg-[#0F0F0F] rounded-2xl p-6 text-white">
            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Clientes VIP</p>
            <p class="text-3xl font-bold mt-2">156</p>
            <p class="text-xs text-gray-400 font-semibold mt-2">Gasto > $20,000</p>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

        {{-- Buscador --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" placeholder="Buscar por nombre, teléfono, email o RFC..."
                    class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D32030] focus:border-transparent focus:bg-white transition">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 text-left">Nombre</th>
                        <th class="px-6 py-4 text-left">Teléfono</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Última Compra</th>
                        <th class="px-6 py-4 text-left">Total Compras</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($clientesDemo as $c)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $c['vip'] ? 'bg-[#D32030]' : 'bg-gray-200 text-gray-600' }} {{ $c['vip'] ? 'text-white' : '' }} flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($c['nombre'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                                            {{ $c['nombre'] }}
                                            @if($c['vip'])
                                                <span class="text-[9px] font-bold text-[#D32030] bg-red-50 border border-red-200 px-1.5 py-0.5 rounded-full uppercase">VIP</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">ID: #C-{{ str_pad($c['id'], 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $c['telefono'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $c['email'] }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $c['ultima'] }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">${{ number_format($c['compras'], 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="#" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Ver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="#" class="p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="#" class="p-2 text-gray-400 hover:text-[#D32030] hover:bg-red-50 rounded-lg transition" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación (visual) --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">Mostrando 5 de 1,284 clientes</p>
            <div class="flex items-center gap-1">
                <button class="px-3 py-1.5 text-sm text-gray-400 hover:bg-gray-100 rounded-lg transition">Anterior</button>
                <button class="px-3 py-1.5 text-sm bg-[#D32030] text-white rounded-lg">1</button>
                <button class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">2</button>
                <button class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">3</button>
                <button class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">Siguiente</button>
            </div>
        </div>
    </div>
</div>
@endsection