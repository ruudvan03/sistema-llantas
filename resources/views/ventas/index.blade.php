@extends('layouts.app')

@section('header_title', 'Punto de Venta')

@section('content')
<div x-data="puntoVenta()" class="flex gap-6 h-[calc(100vh-130px)]">

    {{-- IZQUIERDA: Buscador + filtros + productos --}}
    <div class="flex-1 flex flex-col bg-white rounded-2xl border border-gray-200 overflow-hidden">

        <div class="px-6 pt-5 pb-4 border-b border-gray-100 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Productos</h2>
                    <p class="text-xs text-gray-400 mt-1" x-text="productosFiltrados().length + ' de ' + productos.length + ' productos'"></p>
                </div>
                <div class="flex gap-3">
                    <button @click="limpiarFiltros()" x-show="hayFiltrosActivos()"
                        class="text-xs text-red-500 border border-red-200 bg-red-50 px-3 py-2 rounded-lg font-medium hover:bg-red-100 transition">
                        ✕ Limpiar filtros
                    </button>
                    <button @click="abrirServicios = true"
                        class="text-sm bg-violet-50 text-violet-700 border border-violet-200 px-4 py-2 rounded-lg font-medium hover:bg-violet-100 transition">
                        + Agregar servicio
                    </button>
                </div>
            </div>

            {{-- Buscador --}}
            <div class="relative">
                <input x-model="busqueda" type="text" placeholder="Buscar por medida o marca... (ej: 185/65 R15, Firestone)"
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent focus:bg-white transition">
                <svg class="absolute left-4 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- FILTROS en una sola línea --}}
            <div class="flex items-center gap-4 flex-wrap">
                {{-- Marca (dropdown) --}}
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

                {{-- Uso --}}
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Uso</span>
                    <div class="flex gap-1.5">
                        <button @click="filtroUso = ''" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition"
                            :class="filtroUso === '' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-500 border-gray-200 hover:border-blue-300'">
                            Todos
                        </button>
                        <template x-for="u in usos" :key="u">
                            <button @click="filtroUso = u" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition"
                                :class="filtroUso === u ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-500 border-gray-200 hover:border-blue-300'"
                                x-text="u">
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">Stock</span>
                    <div class="flex gap-1.5">
                        <button @click="filtroStock = ''" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition"
                            :class="filtroStock === '' ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-500 border-gray-200 hover:border-gray-400'">
                            Todos
                        </button>
                        <button @click="filtroStock = 'disponible'" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition"
                            :class="filtroStock === 'disponible' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-500 border-gray-200 hover:border-emerald-300'">
                            Con stock
                        </button>
                        <button @click="filtroStock = 'poco'" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition"
                            :class="filtroStock === 'poco' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-gray-500 border-gray-200 hover:border-amber-300'">
                            Poco
                        </button>
                        <button @click="filtroStock = 'agotado'" class="px-3 py-1.5 rounded-full text-[11px] font-medium border transition"
                            :class="filtroStock === 'agotado' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-gray-500 border-gray-200 hover:border-red-300'">
                            Agotado
                        </button>
                    </div>
                </div>

                {{-- Precio (dropdown) --}}
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
        </div>

        {{-- Lista de productos --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-2 xl:grid-cols-3 gap-3">
                <template x-for="p in productosFiltrados()" :key="p.id">
                    <button @click="agregar(p)" :disabled="p.stock_cantidad <= 0"
                        class="text-left p-4 rounded-xl border transition-all"
                        :class="p.stock_cantidad > 0
                            ? 'border-gray-200 hover:border-emerald-400 hover:bg-emerald-50/50 hover:shadow-md cursor-pointer'
                            : 'border-gray-100 bg-gray-50 opacity-40 cursor-not-allowed'">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600" x-text="p.tipo"></span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                                :class="p.stock_cantidad > 5 ? 'bg-emerald-50 text-emerald-600' : p.stock_cantidad > 0 ? 'bg-amber-50 text-amber-600' : 'bg-red-50 text-red-500'"
                                x-text="p.stock_cantidad > 0 ? 'Stock: ' + p.stock_cantidad : 'Agotado'"></span>
                        </div>
                        <div class="font-bold text-sm text-gray-800 leading-tight" x-text="p.marca"></div>
                        <div class="text-xs text-gray-400 font-mono mt-1" x-text="p.medida"></div>
                        <div class="text-base font-bold text-emerald-600 mt-2" x-text="'$' + precioDe(p).toLocaleString()"></div>
                    </button>
                </template>
            </div>
            <div x-show="productosFiltrados().length === 0" class="text-center text-gray-400 py-20 text-sm">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                No se encontraron productos con esos filtros
            </div>
        </div>
    </div>

    {{-- DERECHA: Carrito --}}
    <div class="w-[420px] flex flex-col bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-5 bg-gray-900 text-white">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-lg">Venta actual</h3>
                <span class="text-xs bg-white/10 px-3 py-1 rounded-full" x-text="carrito.length + ' productos'"></span>
            </div>
            <div class="flex gap-2 mt-4">
                <button @click="cambiarPrecio('menudeo')" class="flex-1 py-2 rounded-lg text-xs font-semibold transition"
                    :class="tipoPrecio === 'menudeo' ? 'bg-emerald-500 text-white' : 'bg-white/10 text-gray-300 hover:bg-white/20'">
                    Menudeo
                </button>
                <button @click="cambiarPrecio('mayoreo')" class="flex-1 py-2 rounded-lg text-xs font-semibold transition"
                    :class="tipoPrecio === 'mayoreo' ? 'bg-emerald-500 text-white' : 'bg-white/10 text-gray-300 hover:bg-white/20'">
                    Mayoreo
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            <template x-for="(item, i) in carrito" :key="i">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-800 truncate" x-text="item.nombre"></div>
                        <div class="flex items-center gap-2 mt-1">
                            <span x-show="item.es_servicio" class="text-[9px] bg-violet-100 text-violet-700 px-2 py-0.5 rounded-full font-bold">SERVICIO</span>
                            <span class="text-xs text-gray-400" x-text="'$' + item.precio_unitario.toLocaleString() + ' c/u'"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button @click="item.cantidad > 1 ? item.cantidad-- : carrito.splice(i,1)"
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-100 transition flex items-center justify-center">&minus;</button>
                        <span class="w-8 text-center text-sm font-bold text-gray-800" x-text="item.cantidad"></span>
                        <button @click="item.cantidad++"
                            class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-100 transition flex items-center justify-center">+</button>
                    </div>
                    <div class="w-20 text-right text-sm font-bold text-gray-800" x-text="'$' + (item.cantidad * item.precio_unitario).toLocaleString()"></div>
                    <button @click="carrito.splice(i,1)" class="text-gray-300 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </template>
            <div x-show="carrito.length === 0" class="text-center py-24">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="text-gray-300 text-sm">Agrega productos a la venta</p>
            </div>
        </div>

        {{-- Totales y cobro --}}
        <div class="border-t border-gray-100 p-5 space-y-4">
            <div class="bg-emerald-500 rounded-2xl p-5 flex justify-between items-center">
                <span class="text-white text-sm font-semibold tracking-wide">TOTAL</span>
                <span class="text-white text-4xl font-bold tracking-tight" x-text="'$' + total().toLocaleString()"></span>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-[11px] text-gray-400 font-semibold block mb-1.5 tracking-wide">PAGA CON</label>
                    <input x-model.number="pagoCon" type="number" placeholder="0"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">
                </div>
                <div>
                    <label class="text-[11px] text-gray-400 font-semibold block mb-1.5 tracking-wide">CAMBIO</label>
                    <div class="px-4 py-2.5 rounded-xl text-lg font-bold"
                        :class="cambio() > 0 && pagoCon > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-50 text-gray-300 border border-gray-200'"
                        x-text="'$' + cambio().toLocaleString()">
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <template x-for="b in [100, 200, 500, 1000]">
                    <button @click="pagoCon = b"
                        class="flex-1 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 font-semibold transition"
                        x-text="'$' + b"></button>
                </template>
                <button @click="pagoCon = total()"
                    class="flex-1 py-2 text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl hover:bg-emerald-100 font-semibold transition">
                    Exacto
                </button>
            </div>

            <input x-model="cliente" type="text" placeholder="Nombre del cliente (opcional)"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white transition">

            <label class="flex items-center gap-3 text-sm text-gray-500 cursor-pointer">
                <input x-model="requiereFactura" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                Requiere factura
            </label>

            <button @click="cobrar()" :disabled="carrito.length === 0"
                class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold text-base hover:bg-black transition disabled:opacity-30 disabled:cursor-not-allowed">
                Cobrar venta
            </button>
        </div>
    </div>

    {{-- MODAL SERVICIOS --}}
    <div x-show="abrirServicios" x-cloak x-transition.opacity
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click.self="abrirServicios = false">
        <div class="bg-white rounded-2xl p-7 w-[460px] shadow-2xl" x-transition.scale>
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-bold text-gray-800 text-lg">Agregar servicio</h3>
                <button @click="abrirServicios = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <p class="text-xs text-gray-400 mb-5">Los servicios no descuentan inventario. Se cobran por pieza/llanta.</p>

            <div class="grid grid-cols-3 gap-3 mb-6">
                <template x-for="s in serviciosComunes">
                    <button @click="servNombre = s.nombre; servPrecio = s.precio"
                        class="p-4 rounded-xl border-2 transition-all text-left"
                        :class="servNombre === s.nombre ? 'border-violet-400 bg-violet-50' : 'border-gray-100 hover:border-violet-200 hover:bg-violet-50/50'">
                        <div class="text-sm font-semibold text-gray-800" x-text="s.nombre"></div>
                        <div class="text-xs text-violet-600 font-bold mt-1" x-text="'$' + s.precio + ' c/u'"></div>
                    </button>
                </template>
            </div>

            <label class="text-[11px] text-gray-400 font-semibold block mb-1.5 tracking-wide">SERVICIO</label>
            <input x-model="servNombre" type="text" placeholder="Ej: Balanceo"
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:bg-white transition">

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="text-[11px] text-gray-400 font-semibold block mb-1.5 tracking-wide">PRECIO UNITARIO</label>
                    <input x-model.number="servPrecio" type="number" placeholder="0"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 focus:bg-white transition">
                </div>
                <div>
                    <label class="text-[11px] text-gray-400 font-semibold block mb-1.5 tracking-wide">CANTIDAD</label>
                    <input x-model.number="servCantidad" type="number" min="1" placeholder="1"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 focus:bg-white transition">
                </div>
            </div>

            <div x-show="servPrecio > 0" class="bg-violet-50 rounded-xl p-4 mb-5 flex justify-between items-center border border-violet-100">
                <span class="text-sm font-medium text-violet-700">Subtotal:</span>
                <span class="text-xl font-bold text-violet-700" x-text="'$' + ((servPrecio || 0) * (servCantidad || 1)).toLocaleString()"></span>
            </div>

            <button @click="agregarServicio()"
                class="w-full py-3 bg-violet-600 text-white rounded-xl font-bold hover:bg-violet-700 transition">
                Agregar al carrito
            </button>
        </div>
    </div>
</div>

<style>[x-cloak]{display:none!important;}</style>

<script>
function puntoVenta() {
    return {
        productos: @json($productos),
        carrito: [],
        busqueda: '',
        filtroMarca: '',
        filtroUso: '',
        filtroStock: '',
        filtroPrecio: '',
        tipoPrecio: 'menudeo',
        pagoCon: '',
        cliente: '',
        requiereFactura: false,
        abrirServicios: false,
        servNombre: '',
        servPrecio: '',
        servCantidad: 1,
        serviciosComunes: [
            { nombre: 'Talacha', precio: 40 },
            { nombre: 'Balanceo', precio: 40 },
            { nombre: 'Rotación', precio: 40 },
            { nombre: 'Revisión', precio: 40 },
            { nombre: 'Alineación', precio: 250 },
            { nombre: 'Montaje', precio: 50 },
        ],
        rangosPrecios: [
            { label: '$0-$1,000', min: 0, max: 1000 },
            { label: '$1,000-$3,000', min: 1000, max: 3000 },
            { label: '$3,000-$5,000', min: 3000, max: 5000 },
            { label: '$5,000+', min: 5000, max: 999999 },
        ],

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
            return this.filtroMarca || this.filtroUso || this.filtroStock || this.filtroPrecio || this.busqueda;
        },

        limpiarFiltros() {
            this.filtroMarca = ''; this.filtroUso = ''; this.filtroStock = ''; this.filtroPrecio = ''; this.busqueda = '';
        },

        precioDe(p) {
            return this.tipoPrecio === 'mayoreo' ? (+p.precio_mayoreo || +p.precio_publico) : +p.precio_publico;
        },

        productosFiltrados() {
            let lista = this.productos;

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

            if (this.filtroStock === 'disponible') lista = lista.filter(p => p.stock_cantidad > 5);
            else if (this.filtroStock === 'poco') lista = lista.filter(p => p.stock_cantidad > 0 && p.stock_cantidad <= 5);
            else if (this.filtroStock === 'agotado') lista = lista.filter(p => p.stock_cantidad <= 0);

            if (this.filtroPrecio) {
                const r = this.rangosPrecios.find(r => r.label === this.filtroPrecio);
                if (r) lista = lista.filter(p => { const pr = this.precioDe(p); return pr >= r.min && pr < r.max; });
            }

            if (this.busqueda) {
                const q = this.busqueda.toLowerCase();
                lista = lista.filter(p => (p.marca + ' ' + p.medida + ' ' + p.tipo + ' ' + (p.descripcion || '')).toLowerCase().includes(q));
            }

            return lista;
        },

        agregar(p) {
            if (p.stock_cantidad <= 0) return;
            const existe = this.carrito.find(i => i.producto_id === p.id && !i.es_servicio);
            if (existe) { existe.cantidad++; }
            else {
                this.carrito.push({
                    producto_id: p.id, nombre: p.marca + ' ' + p.medida,
                    precio_unitario: this.precioDe(p), cantidad: 1, es_servicio: false,
                });
            }
        },

        agregarServicio() {
            if (!this.servNombre || !this.servPrecio) { alert('Completa nombre y precio'); return; }
            this.carrito.push({
                producto_id: null, nombre: this.servNombre,
                precio_unitario: +this.servPrecio, cantidad: +this.servCantidad || 1, es_servicio: true,
            });
            this.servNombre = ''; this.servPrecio = ''; this.servCantidad = 1; this.abrirServicios = false;
        },

        cambiarPrecio(tipo) {
            this.tipoPrecio = tipo;
            this.carrito.forEach(item => {
                if (!item.es_servicio) {
                    const prod = this.productos.find(p => p.id === item.producto_id);
                    if (prod) item.precio_unitario = this.precioDe(prod);
                }
            });
        },

        total() { return this.carrito.reduce((a, i) => a + i.cantidad * i.precio_unitario, 0); },
        cambio() { const c = (+this.pagoCon || 0) - this.total(); return c > 0 ? c : 0; },

        cobrar() {
            if (this.carrito.length === 0) return;
            alert('🛒 Venta por $' + this.total().toLocaleString() + '\n\nProductos: ' + this.carrito.length +
                '\nPaga con: $' + (+this.pagoCon || 0).toLocaleString() + '\nCambio: $' + this.cambio().toLocaleString() +
                '\n\nPendiente conectar backend.');
        }
    }
}
</script>
@endsection