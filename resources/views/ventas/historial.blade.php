@extends('layouts.app')

@section('header_title', 'Historial de Ventas')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-6">
        <form method="GET" action="{{ route('ventas.historial') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Folio del Ticket</label>
                <input type="text" name="folio" value="{{ request('folio') }}" placeholder="Ej. VNT-2026..." 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
            </div>

            @if($esAdmin)
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Sucursal</label>
                <select name="sucursal_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
                    <option value="">Todas las sucursales</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}" {{ request('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex gap-2 lg:col-span-4 justify-end mt-2">
                <a href="{{ route('ventas.historial') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-50 transition">
                    Limpiar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-xl hover:bg-black transition shadow-sm">
                    Buscar Ventas
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="p-4 text-xs font-black text-gray-500 uppercase tracking-wider">Folio / Fecha</th>
                        <th class="p-4 text-xs font-black text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="p-4 text-xs font-black text-gray-500 uppercase tracking-wider">Sucursal / Cajero</th>
                        <th class="p-4 text-xs font-black text-gray-500 uppercase tracking-wider">Artículos</th>
                        <th class="p-4 text-right text-xs font-black text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="p-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ventas as $venta)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4">
                            <div class="font-bold text-gray-900">{{ $venta->folio }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($venta->fecha)->format('d M, Y - H:i') }}</div>
                        </td>
                        <td class="p-4">
                            <div class="text-sm text-gray-800 font-medium">{{ $venta->cliente ?: 'Público General' }}</div>
                            @if($venta->requiere_factura)
                                <span class="inline-flex mt-1 text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Req. Factura</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="text-sm font-medium text-gray-800">Sucursal {{ $venta->sucursal_id }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Cajero ID: {{ $venta->usuario_id }}</div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm font-bold text-gray-700">{{ $venta->detalles->sum('cantidad') }}</span>
                                <span class="text-xs text-gray-400">pzas</span>
                            </div>
                        </td>
                        <td class="p-4 text-right">
                            <div class="font-black text-emerald-600">${{ number_format($venta->total, 2) }}</div>
                        </td>
                        <td class="p-4 text-center">
                            <button onclick="window.open('{{ route('ventas.ticket', $venta->id) }}', 'Ticket', 'width=400,height=600')" 
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-emerald-500 hover:text-white transition-colors" title="Reimprimir Ticket">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-gray-500 font-medium">No se encontraron ventas con los filtros actuales.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($ventas->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $ventas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection