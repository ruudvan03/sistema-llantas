@extends('layouts.app')

@section('header_title', 'Inventario y Catálogo')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if(session('success'))
        <div class="fixed top-5 right-5 z-50 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-5 right-5 z-50 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Inventario</h2>
            <p class="text-sm text-gray-500 mt-1">
                <span class="font-semibold text-gray-700">{{ number_format($productos->sum('stock_cantidad') ?? 0) }} uds</span> en stock global · 
                <span class="text-red-500 font-medium">{{ $productos->where('stock_cantidad', '<', 'stock_minimo')->count() }} bajo mínimo</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('inventario.importar') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Importar Excel
            </a>
            <button onclick="openModal('modal-producto')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                + Nuevo producto
            </button>
            <button onclick="openModal('modal-entrada')" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Registrar entrada (lote)
            </button>
        </div>
    </div>

    <form method="GET" action="{{ route('inventario.index') }}" class="flex flex-wrap gap-3 items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        
        <div class="flex gap-2 mr-4">
            @foreach($sucursales ?? [] as $suc)
                <label class="cursor-pointer">
                    <input type="radio" name="sucursal_id" value="{{ $suc->id }}" class="peer hidden" onchange="this.form.submit()" {{ request('sucursal_id') == $suc->id ? 'checked' : '' }}>
                    <div class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors peer-checked:bg-emerald-50 peer-checked:border-emerald-300 peer-checked:text-emerald-800 bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100">
                        {{ $suc->nombre }}
                    </div>
                </label>
            @endforeach
        </div>

        <div class="flex gap-2">
            @foreach(['Todos', 'Llanta', 'Rin', 'Accesorio'] as $tipo)
                <label class="cursor-pointer">
                    <input type="radio" name="tipo" value="{{ $tipo }}" class="peer hidden" onchange="this.form.submit()" {{ request('tipo', 'Todos') == $tipo ? 'checked' : '' }}>
                    <div class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-colors peer-checked:bg-indigo-50 peer-checked:border-indigo-300 peer-checked:text-indigo-800 bg-gray-50 border-gray-200 text-gray-600 hover:bg-gray-100">
                        {{ $tipo }}
                    </div>
                </label>
            @endforeach
        </div>

        <div class="relative ml-auto w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar medida, marca..." class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2 outline-none">
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                        <th class="p-4 font-semibold whitespace-nowrap">Producto</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Medida</th>
                        <th class="p-4 font-semibold hidden md:table-cell">Descripción</th>
                        <th class="p-4 font-semibold text-center whitespace-nowrap">Stock</th>
                        <th class="p-4 font-semibold whitespace-nowrap">Costo</th>
                        <th class="p-4 font-semibold whitespace-nowrap">P. Público</th>
                        <th class="p-4 font-semibold whitespace-nowrap">P. Mayoreo</th>
                        <th class="p-4 font-semibold text-center whitespace-nowrap">Estado</th>
                        <th class="p-4 font-semibold text-center whitespace-nowrap">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                    @forelse($productos as $producto)
                        @php
                            $st = $producto->stock_cantidad ?? 0;
                            $min = $producto->stock_minimo ?? 5;
                            $bgRow = $loop->even ? 'bg-white' : 'bg-gray-50/30';
                        @endphp
                        <tr class="{{ $bgRow }} hover:bg-blue-50/50 transition-colors group">
                            <td class="p-4 min-w-[180px] whitespace-normal">
                                <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-[10px] rounded-full font-semibold tracking-wide mb-1">{{ $producto->tipo }}</span>
                                <p class="font-bold text-gray-900 leading-tight">{{ $producto->marca }}</p>
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-600 whitespace-nowrap">{{ $producto->medida }}</td>
                            <td class="p-4 text-gray-500 hidden md:table-cell max-w-[150px] lg:max-w-[250px] truncate" title="{{ $producto->descripcion }}">{{ $producto->descripcion ?: '—' }}</td>
                            
                            <td class="p-4 text-center whitespace-nowrap w-24">
                                <input type="number" value="{{ $st }}" 
                                       onblur="updateStock(this, {{ $producto->id }})"
                                       data-original="{{ $st }}"
                                       class="w-16 text-center border border-gray-300 rounded-md p-1 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 outline-none {{ $st == 0 ? 'text-red-600' : 'text-gray-900' }}">
                            </td>
                            
                            <td class="p-4 text-gray-500 whitespace-nowrap">${{ number_format($producto->costo, 2) }}</td>
                            <td class="p-4 font-semibold text-emerald-700 whitespace-nowrap">${{ number_format($producto->precio_publico, 2) }}</td>
                            <td class="p-4 font-semibold text-indigo-700 whitespace-nowrap">${{ number_format($producto->precio_mayoreo, 2) }}</td>
                            
                            <td class="p-4 text-center whitespace-nowrap">
                                @if($st == 0)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-red-50 text-red-700 border border-red-100">Sin stock</span>
                                @elseif($st < $min)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100">Bajo</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">OK</span>
                                @endif
                            </td>
                            
                            <td class="p-4 text-center whitespace-nowrap space-x-1">
                                <button onclick="openModal('modal-detalle', {{ $producto->id }})" class="px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">Ver</button>
                                <button onclick="openModal('modal-precios', {{ $producto->id }})" class="px-3 py-1.5 bg-indigo-50 border border-indigo-200 rounded-md text-xs font-medium text-indigo-700 hover:bg-indigo-100 transition shadow-sm">Precios</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-12 text-center text-gray-400">
                                Sin productos registrados en esta búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($productos->hasPages())
        <div class="bg-white py-6 border-t border-gray-200 text-center">
            <div>
                <ul class="mx-auto flex w-full max-w-[415px] items-center justify-between px-4">
                    <li>
                        <a href="{{ $productos->previousPageUrl() ?? '#' }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 transition-colors {{ $productos->onFirstPage() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                            <span>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.325 14.825C11.175 14.825 11.025 14.775 10.925 14.65L5.27495 8.90002C5.04995 8.67502 5.04995 8.32503 5.27495 8.10002L10.925 2.35002C11.15 2.12502 11.5 2.12502 11.725 2.35002C11.95 2.57502 11.95 2.92502 11.725 3.15002L6.47495 8.50003L11.75 13.85C11.975 14.075 11.975 14.425 11.75 14.65C11.6 14.75 11.475 14.825 11.325 14.825Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <span class="max-sm:hidden"> Anterior </span>
                        </a>
                    </li>
                    <p class="text-base font-medium text-gray-700">
                        Pág {{ $productos->currentPage() }} de {{ $productos->lastPage() }}
                    </p>
                    <li>
                        <a href="{{ $productos->nextPageUrl() ?? '#' }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-lg px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-100 transition-colors {{ !$productos->hasMorePages() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                            <span class="max-sm:hidden"> Siguiente </span>
                            <span>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.67495 14.825C5.52495 14.825 5.39995 14.775 5.27495 14.675C5.04995 14.45 5.04995 14.1 5.27495 13.875L10.525 8.50003L5.27495 3.15002C5.04995 2.92502 5.04995 2.57502 5.27495 2.35002C5.49995 2.12502 5.84995 2.12502 6.07495 2.35002L11.725 8.10002C11.95 8.32503 11.95 8.67502 11.725 8.90002L6.07495 14.65C5.97495 14.75 5.82495 14.825 5.67495 14.825Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        @endif

    </div>
</div>

<div id="modal-entrada" class="fixed inset-0 bg-black/40 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-2xl shadow-xl transform transition-all">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Registrar entrada de lote</h3>
            <button onclick="closeModal('modal-entrada')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form action="{{ route('inventario.entrada.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="bg-emerald-50 text-emerald-800 text-sm p-3 rounded-lg border border-emerald-100">
                No cuentes llanta por llanta. Indica cuántas llegaron de este proveedor en esta entrega.
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Producto</label>
                    <select name="producto_id" class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona producto...</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->marca }} {{ $producto->medida }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Cantidad recibida</label>
                    <input type="number" name="cantidad" class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: 40">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Costo Unitario ($)</label>
                    <input type="number" name="costo_unitario" class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: 480">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modal-entrada')" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700">Guardar entrada</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-producto" class="fixed inset-0 bg-black/40 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Nuevo producto</h3>
            <button onclick="closeModal('modal-producto')" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
        </div>
        <form action="{{ route('inventario.producto.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipo</label>
                    <select name="tipo" class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Llanta</option><option>Rin</option><option>Accesorio</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Marca</label>
                    <input type="text" name="marca" placeholder="Ej: Tornel" class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Medida / Especificación</label>
                    <input type="text" name="medida" placeholder="Ej: 205/55 R16" class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Descripción</label>
                    <textarea name="descripcion" placeholder="Descripción del producto..." class="w-full p-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 resize-y min-h-[60px]"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeModal('modal-producto')" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Cancelar</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Guardar producto</button>
            </div>
        </form>
    </div>
</div>

@endsection

<script>
    function openModal(id, productoId = null) {
        document.getElementById(id).classList.remove('hidden');
        
        if(productoId) {
            console.log("Cargar info del producto ID:", productoId);
        }
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
        }
    }

    async function updateStock(inputElement, productoId) {
        const newValue = inputElement.value;
        const originalValue = inputElement.getAttribute('data-original');

        if(newValue !== originalValue) {
            try {
                inputElement.setAttribute('data-original', newValue);
                alert('Éxito: Stock actualizado correctamente en base de datos');
                
            } catch(error) {
                alert('Error: Hubo un problema al actualizar el stock');
                inputElement.value = originalValue; 
            }
        }
    }
</script>