@extends('layouts.app')

@section('header_title', 'Inventario y Catálogo')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if(session('success'))
        <div class="fixed top-5 right-5 z-50 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-lg flex items-center animate-fade-in">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-5 right-5 z-50 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg flex items-center animate-fade-in">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Inventario</h2>
            <p class="text-sm text-gray-500 mt-1">Gestión avanzada del catálogo general de mercancía.</p>
        </div>
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <a href="{{ route('inventario.importar') }}" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition">Importar Excel</a>
            <button onclick="openModal('modal-producto')" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">+ Nuevo producto</button>
            <button onclick="openModal('modal-entrada')" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">Registrar entrada</button>
        </div>
    </div>

    <form id="filter-form" method="GET" action="{{ route('inventario.index') }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
            
            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Búsqueda Inteligente</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Medida, marca..." class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 block w-full p-2.5 outline-none">
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Estado del Stock</label>
                <select name="stock_status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 block w-full p-2.5 outline-none">
                    <option value="">Todos los niveles</option>
                    <option value="sin_stock" {{ request('stock_status') == 'sin_stock' ? 'selected' : '' }}>❌ Sin Stock (0 uds)</option>
                    <option value="bajo_stock" {{ request('stock_status') == 'bajo_stock' ? 'selected' : '' }}>⚠️ Bajo Mínimo</option>
                    <option value="ok" {{ request('stock_status') == 'ok' ? 'selected' : '' }}>✅ Stock OK</option>
                </select>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Filtrar por Marca</label>
                <select name="marca_filtro" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 block w-full p-2.5 outline-none">
                    <option value="Todas">Todas las marcas</option>
                    @foreach($marcasDisponibles ?? [] as $m)
                        <option value="{{ $m }}" {{ request('marca_filtro') == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ordenar Comercial</label>
                <select name="ordenar_precio" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 block w-full p-2.5 outline-none">
                    <option value="">Por Defecto (Marca/Medida)</option>
                    <option value="publico_mayor" {{ request('ordenar_precio') == 'publico_mayor' ? 'selected' : '' }}>💰 P. Público: Mayor a Menor</option>
                    <option value="publico_menor" {{ request('ordenar_precio') == 'publico_menor' ? 'selected' : '' }}>💰 P. Público: Menor a Mayor</option>
                    <option value="mayoreo_mayor" {{ request('ordenar_precio') == 'mayoreo_mayor' ? 'selected' : '' }}>🏢 P. Mayoreo: Mayor a Menor</option>
                    <option value="mayoreo_menor" {{ request('ordenar_precio') == 'mayoreo_menor' ? 'selected' : '' }}>🏢 P. Mayoreo: Menor a Mayor</option>
                    <option value="costo_mayor" {{ request('ordenar_precio') == 'costo_mayor' ? 'selected' : '' }}>📉 Costo Compra: Mayor a Menor</option>
                    <option value="costo_menor" {{ request('ordenar_precio') == 'costo_menor' ? 'selected' : '' }}>📉 Costo Compra: Menor a Mayor</option>
                </select>
            </div>
        </div>

        <hr class="border-gray-100">

        <div class="flex flex-col gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mr-1">Sucursal:</span>
                <label class="relative block cursor-pointer">
                    <input type="radio" name="sucursal_id" value="" onchange="this.form.submit()" class="peer absolute inset-0 opacity-0 cursor-pointer z-10" {{ !request()->filled('sucursal_id') ? 'checked' : '' }}>
                    <div class="px-3 py-1 rounded-full text-xs font-semibold border transition-all peer-checked:bg-emerald-600 peer-checked:border-emerald-600 peer-checked:text-white bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100">Todas</div>
                </label>
                @foreach($sucursales ?? [] as $suc)
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="sucursal_id" value="{{ $suc->id }}" onchange="this.form.submit()" class="peer absolute inset-0 opacity-0 cursor-pointer z-10" {{ request('sucursal_id') == $suc->id ? 'checked' : '' }}>
                        <div class="px-3 py-1 rounded-full text-xs font-semibold border transition-all peer-checked:bg-emerald-600 peer-checked:border-emerald-600 peer-checked:text-white bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100">{{ $suc->nombre }}</div>
                    </label>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mr-1">Categoría:</span>
                @foreach(['Todos', 'Llanta', 'Rin', 'Accesorio'] as $tipo)
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="tipo" value="{{ $tipo }}" onchange="this.form.submit()" class="peer absolute inset-0 opacity-0 cursor-pointer z-10" {{ request('tipo', 'Todos') == $tipo ? 'checked' : '' }}>
                        <div class="px-3 py-1 rounded-full text-xs font-semibold border transition-all peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100">{{ $tipo }}</div>
                    </label>
                @endforeach
            </div>
        </div>
        
        @if(request()->hasAny(['sucursal_id', 'tipo', 'q', 'stock_status', 'marca_filtro', 'ordenar_precio']))
            <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                <button type="submit" class="px-5 py-1.5 bg-blue-600 text-white font-medium rounded-lg text-xs hover:bg-blue-700 transition">Aplicar Filtros Manuales</button>
                <a href="{{ route('inventario.index') }}" class="inline-flex items-center text-xs font-semibold text-red-500 hover:text-red-700 transition">
                    ❌ Borrar todos los filtros aplicados
                </a>
            </div>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-semibold whitespace-nowrap">Producto</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Medida</th>
                        <th class="p-4 font-semibold text-center whitespace-nowrap">Stock</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Costo Compra</th>
                        <th class="p-4 font-semibold whitespace-nowrap">P. Público</th>
                        <th class="p-4 font-semibold whitespace-nowrap">P. Mayoreo</th>
                        <th class="p-4 font-semibold text-center whitespace-nowrap">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse($productos as $producto)
                        @php
                            $st = $producto->stock_cantidad ?? 0;
                            $min = $producto->stock_minimo ?? 5;
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="p-4">
                                <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] rounded-full font-bold tracking-wide mb-1">{{ $producto->tipo }}</span>
                                <p class="font-bold text-gray-900 leading-tight">{{ $producto->marca }}</p>
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-600 whitespace-nowrap">{{ $producto->medida }}</td>
                            <td class="p-4 text-center whitespace-nowrap w-24">
                                <span class="w-16 px-3 py-1 bg-gray-100 border border-gray-200 text-gray-900 rounded-md text-sm font-bold block mx-auto">{{ $st }}</span>
                            </td>
                            <td class="p-4 text-gray-500 whitespace-nowrap">${{ number_format($producto->costo, 2) }}</td>
                            <td class="p-4 font-semibold text-emerald-700 whitespace-nowrap">${{ number_format($producto->precio_publico, 2) }}</td>
                            <td class="p-4 font-semibold text-indigo-700 whitespace-nowrap">${{ number_format($producto->precio_mayoreo, 2) }}</td>
                            <td class="p-4 text-center whitespace-nowrap">
                                @if($st == 0)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-red-50 text-red-700 border border-red-100">Agotado</span>
                                @elseif($st < $min)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100">Bajo</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">OK</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-12 text-center text-gray-400">Sin registros bajo esos filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($productos->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $productos->links() }}
            </div>
        @endif
    </div>
</div>

<div id="modal-entrada" class="fixed inset-0 bg-black/40 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Registrar Entrada</h3>
            <button onclick="closeModal('modal-entrada')" class="text-gray-400 text-xl">&times;</button>
        </div>
        <form action="{{ route('inventario.entrada.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Producto</label>
                <select name="producto_id" class="w-full p-2 border border-gray-300 rounded-lg text-sm">
                    @foreach($productos as $p) <option value="{{ $p->id }}">{{ $p->marca }} - {{ $p->medida }}</option> @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cantidad</label>
                    <input type="number" name="cantidad" class="w-full p-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Costo Unitario ($)</label>
                    <input type="number" name="costo_unitario" step="0.01" class="w-full p-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal('modal-entrada')" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Registrar</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-producto" class="fixed inset-0 bg-black/40 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Nuevo Producto</h3>
            <button onclick="closeModal('modal-producto')" class="text-gray-400 text-xl">&times;</button>
        </div>
        <form action="{{ route('inventario.producto.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
                    <select name="tipo" class="w-full p-2 border border-gray-300 rounded-lg text-sm"><option>Llanta</option><option>Rin</option><option>Accesorio</option></select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Marca</label>
                    <input type="text" name="marca" class="w-full p-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Medida</label>
                    <input type="text" name="medida" class="w-full p-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModal('modal-producto')" class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
</script>
@endsection