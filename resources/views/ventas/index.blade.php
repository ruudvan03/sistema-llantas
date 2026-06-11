@extends('layouts.app')

@section('header_title', 'Punto de Venta')

@section('content')
<div x-data="puntoVenta()" class="flex flex-col lg:flex-row gap-4 lg:gap-6 h-auto lg:h-[calc(100vh-130px)] pb-6 lg:pb-0">

    <div class="flex-1 flex flex-col bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm h-[65vh] lg:h-full">

        <div class="px-4 sm:px-6 pt-5 pb-4 border-b border-gray-100 space-y-4 shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Productos y Servicios</h2>
                        <p class="text-xs text-gray-400 mt-1" x-text="productosFiltrados().length + ' de ' + productos.length + ' registros'"></p>
                    </div>
                    
                    @if($esAdmin)
                        <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl shadow-sm">
                            <span class="text-[10px] font-black text-amber-800 uppercase tracking-wider">Sucursal Activa:</span>
                            <select x-model="sucursalSeleccionada" 
                                class="text-xs font-bold bg-white border border-amber-300 rounded-lg p-1 text-gray-700 focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                                @foreach($sucursales as $suc)
                                    <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div>
                    <button @click="limpiarFiltros()" x-show="hayFiltrosActivos()" x-cloak
                        class="text-xs text-red-500 border border-red-200 bg-red-50 px-3 py-2 rounded-lg font-medium hover:bg-red-100 transition flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="hidden sm:inline">Limpiar filtros</span>
                        <span class="sm:hidden">Limpiar</span>
                    </button>
                </div>
            </div>

            <div class="relative">
                <input x-model="busqueda" type="text" placeholder="Buscar por medida o marca..."
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent focus:bg-white transition">
                <svg class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="space-y-3">
                <div class="flex items-center gap-6 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Marca</span>
                        <select x-model="filtroMarca"
                            class="px-3 py-1.5 rounded-lg text-sm border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer min-w-[160px]">
                            <option value="">Todas las marcas</option>
                            <template x-for="m in marcas" :key="m">
                                <option :value="m" x-text="m + ' (' + contarMarca(m) + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Uso</span>
                        <div class="flex gap-1.5 overflow-x-auto scrollbar-hide">
                            <button @click="filtroUso = ''" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition shrink-0"
                                :class="filtroUso === '' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-500 border-gray-200 hover:border-blue-300'">
                                Todos
                            </button>
                            <template x-for="u in usos" :key="u">
                                <button @click="filtroUso = u" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition shrink-0"
                                    :class="filtroUso === u ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-500 border-gray-200 hover:border-blue-300'"
                                    x-text="u">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Stock</span>
                        <div class="flex gap-1.5 overflow-x-auto scrollbar-hide">
                            <button @click="filtroStock = ''" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition shrink-0"
                                :class="filtroStock === '' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400'">
                                Todos
                            </button>
                            <button @click="filtroStock = 'disponible'" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition shrink-0"
                                :class="filtroStock === 'disponible' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-500 border-gray-200 hover:border-emerald-300'">
                                Con stock
                            </button>
                            <button @click="filtroStock = 'poco'" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition shrink-0"
                                :class="filtroStock === 'poco' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-gray-500 border-gray-200 hover:border-amber-300'">
                                Poco
                            </button>
                            <button @click="filtroStock = 'agotado'" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition shrink-0"
                                :class="filtroStock === 'agotado' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-gray-500 border-gray-200 hover:border-red-300'">
                                Agotado
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Precio</span>
                        <select x-model="filtroPrecio"
                            class="px-3 py-1.5 rounded-lg text-sm border border-gray-200 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 cursor-pointer min-w-[140px]">
                            <option value="">Todos los precios</option>
                            <template x-for="r in rangosPrecios" :key="r.label">
                                <option :value="r.label" x-text="r.label"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 w-full pt-2 border-t border-gray-50">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Categoría</span>
                    <div class="flex gap-1.5 overflow-x-auto scrollbar-hide">
                        <template x-for="cat in ['Todos', 'Llanta', 'Rin', 'Accesorio', 'Servicio']" :key="cat">
                            <button @click="filtroCategoria = cat" class="px-4 py-1.5 rounded-full text-[11px] font-bold border transition shrink-0"
                                :class="filtroCategoria === cat ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-500 border-gray-200 hover:border-indigo-300'"
                                x-text="cat">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 sm:p-5 bg-gray-50/50">
            <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-3 gap-4 sm:gap-5">
                <template x-for="p in productosFiltrados()" :key="p.id">
                    <button @click="agregar(p)" :disabled="p.tipo !== 'Servicio' && p.stock_cantidad <= 0"
                        class="flex flex-col text-left p-5 rounded-2xl border transition-all h-full"
                        :class="(p.tipo === 'Servicio' || p.stock_cantidad > 0)
                            ? 'border-gray-200 bg-white hover:border-emerald-400 hover:bg-emerald-50/50 hover:shadow-md cursor-pointer'
                            : 'border-gray-100 bg-gray-50 opacity-50 cursor-not-allowed'">
                        
                        <div class="w-full">
                            <div class="flex items-start justify-between mb-3">
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider" 
                                    :class="p.tipo === 'Servicio' ? 'bg-violet-100 text-violet-700' : 'bg-blue-100 text-blue-700'" 
                                    x-text="p.tipo"></span>
                                
                                <template x-if="p.tipo !== 'Servicio'">
                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full"
                                        :class="p.stock_cantidad > 5 ? 'bg-emerald-100 text-emerald-700' : p.stock_cantidad > 0 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600'"
                                        x-text="p.stock_cantidad > 0 ? 'Stock: ' + p.stock_cantidad : 'Agotado'"></span>
                                </template>
                            </div>
                            
                            <div class="font-bold text-base text-gray-800 leading-tight mb-1" x-text="p.tipo === 'Servicio' ? p.descripcion : p.marca"></div>
                            
                            <template x-if="p.tipo !== 'Servicio'">
                                <div class="text-xs text-gray-500 font-mono" x-text="p.medida"></div>
                            </template>
                            <template x-if="p.tipo === 'Servicio'">
                                <div class="text-xs text-gray-500 truncate" x-text="p.descripcion"></div>
                            </template>
                        </div>

                        <div class="w-full mt-4 pt-3 border-t border-gray-50">
                            <div class="text-lg font-black text-emerald-600" x-text="'$' + (+p.precio_publico).toLocaleString()"></div>
                        </div>
                    </button>
                </template>
            </div>
            
            <div x-show="productosFiltrados().length === 0" class="text-center text-gray-400 py-20 text-sm" x-cloak>
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                No se encontraron registros con los filtros actuales.
            </div>
        </div>
    </div>

    <div class="w-full lg:w-[380px] xl:w-[420px] flex flex-col bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm shrink-0 h-[65vh] lg:h-full">
        <div class="p-4 bg-gray-900 text-white border-b border-gray-800 shrink-0">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-lg">Venta actual</h3>
                <span class="text-xs bg-white/10 px-3 py-1 rounded-full font-medium" x-text="carrito.length + ' partidas'"></span>
            </div>
            <div class="flex items-center gap-2 mt-2 text-xs">
                <div class="px-2 py-1 rounded bg-white/10 font-mono" x-text="totalLlantas + ' llantas'"></div>
                <div class="flex-1 text-right font-medium" 
                    :class="aplicaMayoreoGlobal ? 'text-emerald-400' : 'text-gray-400'"
                    x-text="aplicaMayoreoGlobal ? 'Precio mayoreo activado' : 'Faltan ' + (20 - totalLlantas) + ' para mayoreo'">
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-2.5 bg-gray-50/50">
            <template x-for="(item, i) in carrito" :key="i">
                <div class="flex flex-col p-3 rounded-xl bg-white border border-gray-200 shadow-sm relative">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0 pr-6">
                            <div class="text-sm font-bold text-gray-800 leading-tight" x-text="item.nombre"></div>
                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                <span x-show="item.tipo === 'Servicio'" class="text-[9px] bg-violet-100 text-violet-700 px-1.5 py-0.5 rounded font-bold tracking-wider">SERVICIO</span>
                                <span class="text-xs font-semibold text-gray-500" x-text="'$' + calcularItem(item).precioUnitario.toLocaleString() + ' c/u'"></span>
                                <span x-show="item.tipo === 'Llanta' && aplicaMayoreoGlobal" class="text-[9px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold tracking-wider border border-emerald-200">MAYOREO</span>
                            </div>
                        </div>
                        <button @click="carrito.splice(i,1)" class="absolute top-2.5 right-2.5 text-gray-300 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-gray-50">
                        <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-0.5 border border-gray-200">
                            <button @click="item.cantidad > 1 ? item.cantidad-- : carrito.splice(i,1)" class="w-6 h-6 rounded bg-white border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-100 transition flex items-center justify-center">&minus;</button>
                            <span class="w-7 text-center text-sm font-bold text-gray-800" x-text="item.cantidad"></span>
                            <button @click="item.cantidad++" class="w-6 h-6 rounded bg-white border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-100 transition flex items-center justify-center">+</button>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black text-gray-900" x-text="'$' + calcularItem(item).totalFinal.toLocaleString()"></div>
                            <div x-show="calcularItem(item).descuento > 0" class="text-[10px] font-bold text-emerald-600 mt-0.5" x-text="'-$' + calcularItem(item).descuento + ' Dto (4x)'"></div>
                        </div>
                    </div>
                </div>
            </template>
            
            <div x-show="carrito.length === 0" class="text-center py-10" x-cloak>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <p class="text-gray-400 font-medium text-xs">El carrito está vacío</p>
            </div>
        </div>

        <div class="border-t border-gray-200 p-4 space-y-3 bg-white shrink-0">
            
            <div x-show="totalAhorro > 0" class="text-xs font-bold text-emerald-600 text-center bg-emerald-50 py-1.5 rounded-lg border border-emerald-100" x-cloak>
                Ahorro total aplicado: $<span x-text="totalAhorro.toLocaleString()"></span>
            </div>

            <div class="bg-emerald-500 rounded-xl p-3.5 flex justify-between items-center shadow-md">
                <span class="text-emerald-50 text-xs font-semibold tracking-wide">TOTAL</span>
                <span class="text-white text-2xl font-black tracking-tight" x-text="'$' + totalGeneral().toLocaleString()"></span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] text-gray-500 font-bold block mb-1 tracking-wider uppercase">Paga con</label>
                    <input x-model.number="pagoCon" type="number" placeholder="0"
                        class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
                </div>
                <div>
                    <label class="text-[10px] text-gray-500 font-bold block mb-1 tracking-wider uppercase">Cambio</label>
                    <div class="px-3 py-2 rounded-lg text-sm font-black flex items-center h-[38px]"
                        :class="cambio() > 0 && pagoCon > 0 ? 'bg-gray-100 text-gray-700 border border-gray-200' : 'bg-gray-50 text-gray-400 border border-gray-100'"
                        x-text="'$' + cambio().toLocaleString()">
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <template x-for="b in [200, 500, 1000, 2000]">
                    <button @click="pagoCon = b"
                        class="flex-1 py-1.5 text-[11px] bg-white border border-gray-200 text-gray-600 rounded-md hover:bg-gray-50 hover:border-gray-300 font-bold transition shadow-sm"
                        x-text="'$' + b"></button>
                </template>
                <button @click="pagoCon = totalGeneral()"
                    class="flex-1 py-1.5 text-[11px] bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md hover:bg-emerald-100 font-bold transition shadow-sm">
                    Exacto
                </button>
            </div>

            <div class="flex items-center gap-3">
                <input x-model="cliente" type="text" placeholder="Cliente (opcional)"
                    class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition">

                <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer whitespace-nowrap">
                    <input x-model="requiereFactura" type="checkbox" class="w-3.5 h-3.5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    Factura
                </label>
            </div>

            <button @click="cobrar()" :disabled="carrito.length === 0"
                class="w-full py-3 bg-gray-400 text-white rounded-xl font-bold text-sm transition disabled:opacity-50 disabled:cursor-not-allowed"
                :class="carrito.length > 0 ? 'bg-gray-900 hover:bg-black' : ''">
                Cobrar venta
            </button>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
function puntoVenta() {
    return {
        productos: @json($productos),
        carrito: [],
        busqueda: '',
        filtroCategoria: 'Todos',
        filtroMarca: '',
        filtroUso: '',
        filtroStock: '',
        filtroPrecio: '',
        pagoCon: '',
        cliente: '',
        requiereFactura: false,
        sucursalSeleccionada: '{{ $sucursalDefecto }}', // Almacena la sucursal activa en Alpine.js
        rangosPrecios: [
            { label: '$0-$1,000', min: 0, max: 1000 },
            { label: '$1,000-$3,000', min: 1000, max: 3000 },
            { label: '$3,000-$5,000', min: 3000, max: 5000 },
            { label: '$5,000+', min: 5000, max: 999999 },
        ],

        init() {
            // Evaluamos stocks al arrancar
            this.actualizarStockPorSucursal();
            
            // Si el Administrador cambia la sucursal activa, recalculamos inventario e invalidamos el carrito
            this.$watch('sucursalSeleccionada', () => {
                this.actualizarStockPorSucursal();
                this.carrito = [];
            });
        },

        actualizarStockPorSucursal() {
            this.productos.forEach(p => {
                if (p.tipo === 'Servicio') {
                    p.stock_cantidad = 999999;
                } else {
                    // Lee dinámicamente el valor mapeado de la base de datos para la sucursal elegida
                    p.stock_cantidad = p.stocks[this.sucursalSeleccionada] || 0;
                }
            });
        },

        get marcas() {
            const m = [...new Set(this.productos.map(p => {
                const marca = (p.marca || '').split(' ')[0].toUpperCase();
                if (!marca || marca.length < 3) return null;
                if (/^[\d\-\.\/]+[A-Z]?$/.test(marca)) return null;
                if (/^\d+[A-Z]+\d*$/.test(marca)) return null;
                return marca;
            }).filter(Boolean))];
            return m.sort();
        },

        get usos() {
            const u = new Set();
            this.productos.forEach(p => {
                const t = ((p.tipo || '') + ' ' + (p.marca || '') + ' ' + (p.descripcion || '')).toUpperCase();
                if (t.includes('DIRECC')) u.add('Dirección');
                if (t.includes('TRACC')) u.add('Tracción');
                if (t.includes('LINEAL')) u.add('Lineal');
            });
            return [...u].sort();
        },

        contarMarca(m) {
            return this.productos.filter(p => (p.marca || '').split(' ')[0].toUpperCase() === m).length;
        },

        hayFiltrosActivos() {
            return this.filtroCategoria !== 'Todos' || this.filtroMarca || this.filtroUso || this.filtroStock || this.filtroPrecio || this.busqueda;
        },

        limpiarFiltros() {
            this.filtroCategoria = 'Todos'; this.filtroMarca = ''; this.filtroUso = ''; this.filtroStock = ''; this.filtroPrecio = ''; this.busqueda = '';
        },

        productosFiltrados() {
            let lista = this.productos;

            if (this.filtroCategoria !== 'Todos') {
                const catBuscada = this.filtroCategoria.trim().toLowerCase();
                lista = lista.filter(p => {
                    const tipoBd = (p.tipo || '').trim().toLowerCase();
                    return tipoBd === catBuscada || tipoBd.includes(catBuscada);
                });
            }

            if (this.filtroMarca) {
                lista = lista.filter(p => (p.marca || '').split(' ')[0].toUpperCase() === this.filtroMarca);
            }

            if (this.filtroUso) {
                lista = lista.filter(p => {
                    const t = ((p.tipo || '') + ' ' + (p.marca || '') + ' ' + (p.descripcion || '')).toUpperCase();
                    if (this.filtroUso === 'Dirección') return t.includes('DIRECC');
                    if (this.filtroUso === 'Tracción') return t.includes('TRACC');
                    if (this.filtroUso === 'Lineal') return t.includes('LINEAL');
                    return true;
                });
            }

            if (this.filtroStock === 'disponible') lista = lista.filter(p => p.tipo === 'Servicio' || p.stock_cantidad > 5);
            else if (this.filtroStock === 'poco') lista = lista.filter(p => p.stock_cantidad > 0 && p.stock_cantidad <= 5 && p.tipo !== 'Servicio');
            else if (this.filtroStock === 'agotado') lista = lista.filter(p => p.tipo !== 'Servicio' && p.stock_cantidad <= 0);

            if (this.filtroPrecio) {
                const r = this.rangosPrecios.find(r => r.label === this.filtroPrecio);
                if (r) lista = lista.filter(p => { const pr = +p.precio_publico; return pr >= r.min && pr < r.max; });
            }

            if (this.busqueda) {
                const q = this.busqueda.toLowerCase();
                lista = lista.filter(p => (p.marca + ' ' + p.medida + ' ' + p.tipo + ' ' + (p.descripcion || '')).toLowerCase().includes(q));
            }

            return lista;
        },

        agregar(p) {
            if (p.tipo !== 'Servicio' && p.stock_cantidad <= 0) return;
            
            const existe = this.carrito.find(i => i.producto_id === p.id && i.tipo !== 'Servicio');
            if (existe) { 
                existe.cantidad++; 
            } else {
                this.carrito.push({
                    producto_id: p.id, 
                    nombre: p.tipo === 'Servicio' ? p.descripcion : (p.marca + ' ' + p.medida),
                    tipo: p.tipo,
                    precio_publico: +p.precio_publico, 
                    precio_mayoreo: +p.precio_mayoreo || +p.precio_publico,
                    cantidad: 1, 
                });
            }
        },

        get totalLlantas() {
            return this.carrito.reduce((suma, item) => item.tipo === 'Llanta' ? suma + item.cantidad : suma, 0);
        },

        get aplicaMayoreoGlobal() {
            return this.totalLlantas >= 20;
        },

        calcularItem(item) {
            let precioUnitario = item.precio_publico;
            
            if (item.tipo === 'Llanta' && this.aplicaMayoreoGlobal) {
                precioUnitario = item.precio_mayoreo;
            }

            let subtotal = precioUnitario * item.cantidad;
            let descuento = 0;

            if (item.tipo === 'Llanta') {
                const bloquesDe4 = Math.floor(item.cantidad / 4);
                descuento = bloquesDe4 * 80;
            }

            return {
                precioUnitario,
                subtotal,
                descuento,
                totalFinal: subtotal - descuento
            };
        },

        totalGeneral() { 
            return this.carrito.reduce((suma, item) => suma + this.calcularItem(item).totalFinal, 0); 
        },

        get totalAhorro() {
            let ahorro = 0;
            this.carrito.forEach(item => {
                if (item.tipo === 'Llanta') {
                    const calculo = this.calcularItem(item);
                    ahorro += calculo.descuento; 
                    
                    if (this.aplicaMayoreoGlobal && item.precio_mayoreo < item.precio_publico) {
                        ahorro += (item.precio_publico - item.precio_mayoreo) * item.cantidad;
                    }
                }
            });
            return ahorro;
        },

        cambio() { 
            const c = (+this.pagoCon || 0) - this.totalGeneral(); 
            return c > 0 ? c : 0; 
        },

        async cobrar() {
            if (this.carrito.length === 0) return;

            let pagoEfectivo = +this.pagoCon || this.totalGeneral();
            if (pagoEfectivo < this.totalGeneral()) {
                alert('El monto ingresado es menor al total de la venta.');
                return;
            }

            const payload = {
                sucursal_id: this.sucursalSeleccionada, // Enviamos qué sucursal realiza la venta
                carrito: this.carrito.map(item => {
                    const calc = this.calcularItem(item);
                    return {
                        producto_id: item.producto_id,
                        nombre: item.nombre,
                        tipo: item.tipo,
                        cantidad: item.cantidad,
                        precio_unitario: calc.precioUnitario,
                        descuento: calc.descuento,
                        subtotal: calc.totalFinal
                    };
                }),
                pagoCon: pagoEfectivo,
                cambio: this.cambio(),
                total: this.totalGeneral(),
                cliente: this.cliente,
                requiereFactura: this.requiereFactura
            };

            try {
                const btn = event.target;
                const textoOriginal = btn.innerText;
                btn.innerText = 'Procesando...';
                btn.disabled = true;

                const ticketWindow = window.open('', 'TicketVenta', 'width=400,height=600');

                const response = await fetch('{{ route("ventas.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    ticketWindow.location.href = data.ticket_url;
                    window.location.reload();
                } else {
                    ticketWindow.close();
                    alert(data.message || 'Error al procesar la venta');
                    btn.innerText = textoOriginal;
                    btn.disabled = false;
                }
            } catch (error) {
                alert('Error de conexión con el servidor.');
                event.target.innerText = 'Cobrar venta';
                event.target.disabled = false;
            }
        }
    }
}
</script>
@endsection