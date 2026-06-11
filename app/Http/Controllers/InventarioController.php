<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\StockSucursal;
use App\Models\Sucursal;
use App\Models\CuentaPagar;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        // 1. IDENTIFICACIÓN DE ROLES Y SUCURSALES
        $empleado = DB::table('usuarios')->where('id', Auth::id())->first();
        $esAdmin = ($empleado && $empleado->rol === 'admin') || Auth::id() === 1;
        $sucursalUsuario = $empleado ? $empleado->sucursal_id : 1;

        // Si es Admin, puede filtrar por la sucursal que elija en la vista. Si es operador, se le fuerza SU sucursal.
        $sucursalFiltro = $esAdmin ? $request->input('sucursal_id') : $sucursalUsuario;

        $query = Producto::query();

        // 2. EXTRAER MARCAS DISPONIBLES DE LA BD (SIEMPRE DISPONIBLE PARA LA VISTA)
        $marcasDisponibles = Producto::select('marca')
            ->whereNotNull('marca')
            ->where('marca', '!=', '')
            ->distinct()
            ->orderBy('marca')
            ->pluck('marca');

        // 3. CÁLCULO DE STOCK ESTRICTO POR SUCURSAL
        if ($sucursalFiltro) {
            $query->withSum(['stock as stock_cantidad' => function($q) use ($sucursalFiltro) {
                $q->where('sucursal_id', $sucursalFiltro);
            }], 'cantidad');

            $query->addSelect(['stock_minimo' => StockSucursal::select('stock_minimo')
                ->whereColumn('producto_id', 'productos.id')
                ->where('sucursal_id', $sucursalFiltro)
                ->limit(1)
            ]);
        } else {
            // Solo un administrador que no ha seleccionado filtro llega aquí (Vista Global)
            $query->withSum('stock as stock_cantidad', 'cantidad');
            $query->addSelect(['stock_minimo' => StockSucursal::select('stock_minimo')
                ->whereColumn('producto_id', 'productos.id')
                ->limit(1)
            ]);
        }

        // 4. FILTRO POR CATEGORÍA / TIPO (CHIPS)
        if ($request->filled('tipo') && $request->tipo !== 'Todos') {
            $query->where('tipo', $request->tipo);
        }

        // 5. FILTRO POR MARCA SELECTORA
        if ($request->filled('marca_filtro') && $request->marca_filtro !== 'Todas') {
            $query->where('marca', $request->marca_filtro);
        }

        // 6. BARRA DE BÚSQUEDA INTELIGENTE GENERAL (Q)
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('marca', 'like', '%' . $request->q . '%')
                  ->orWhere('medida', 'like', '%' . $request->q . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->q . '%');
            });
        }

        // 7. FILTRO POR ESTADO/ALERTA DE STOCK
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'sin_stock':
                    $query->having('stock_cantidad', '=', 0)->orHavingNull('stock_cantidad');
                    break;
                case 'bajo_stock':
                    $query->havingRaw('stock_cantidad < COALESCE(stock_minimo, 5)');
                    break;
                case 'ok':
                    $query->havingRaw('stock_cantidad >= COALESCE(stock_minimo, 5)');
                    break;
            }
        }

        // 8. ORDENAMIENTO AVANZADO POR PRECIOS Y COSTOS (MAYOR / MENOR)
        if ($request->filled('ordenar_precio')) {
            switch ($request->ordenar_precio) {
                case 'costo_mayor': $query->orderBy('costo', 'desc'); break;
                case 'costo_menor': $query->orderBy('costo', 'asc'); break;
                case 'publico_mayor': $query->orderBy('precio_publico', 'desc'); break;
                case 'publico_menor': $query->orderBy('precio_publico', 'asc'); break;
                case 'mayoreo_mayor': $query->orderBy('precio_mayoreo', 'desc'); break;
                case 'mayoreo_menor': $query->orderBy('precio_mayoreo', 'asc'); break;
                default:
                    $query->orderBy('marca')->orderBy('medida');
                    break;
            }
        } else {
            $query->orderBy('marca')->orderBy('medida');
        }

        // Paginación limpia manteniendo los parámetros de las URLs
        $productos = $query->paginate(10)->withQueryString();
        
        // El administrador ve todas las sucursales en su filtro, el operador solo ve la suya
        $sucursales = $esAdmin ? Sucursal::all() : Sucursal::where('id', $sucursalUsuario)->get();

        return view('inventario.index', compact('productos', 'sucursales', 'marcasDisponibles'));
    }

    public function importar() 
    { 
        return view('inventario.importar'); 
    }

    public function procesarImportacion(Request $request)
    {
        $request->validate(['archivo_excel' => 'required|mimes:xlsx,xls,csv,txt|max:10240']);
        
        try {
            $empleado = DB::table('usuarios')->where('id', Auth::id())->first();
            $sucursalUsuario = $empleado ? $empleado->sucursal_id : 1;
            
            // Asigna a la sucursal del usuario automáticamente
            $sucursal_destino = $request->input('sucursal_id', $sucursalUsuario);

            $path = $request->file('archivo_excel')->path();
            $coleccion = (new FastExcel)->withoutHeaders()->import($path);
            $productosProcesados = 0;
            DB::beginTransaction();
            $indices = null;

            foreach ($coleccion as $fila) {
                $valores = array_values($fila);
                if (!$indices) {
                    $tempIndices = [];
                    foreach ($valores as $k => $v) {
                        if ($v === null || trim((string)$v) === '') continue;
                        $valStr = strtolower(preg_replace('/[\s\r\n]+/', '', str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], trim((string)$v))));
                        if (str_contains($valStr, 'descrip')) $tempIndices['descripcion'] = $k;
                        elseif (str_contains($valStr, 'categ') || str_contains($valStr, 'tipo')) $tempIndices['categoria'] = $k;
                        elseif (str_contains($valStr, 'mayor') || str_contains($valStr, 'costo')) $tempIndices['mayoreo'] = $k;
                        elseif (str_contains($valStr, 'public') || str_contains($valStr, 'menude')) $tempIndices['publico'] = $k;
                        elseif (str_contains($valStr, 'stock') || str_contains($valStr, 'actual') || str_contains($valStr, 'cantid')) $tempIndices['stock'] = $k;
                    }
                    if (isset($tempIndices['descripcion']) && (isset($tempIndices['mayoreo']) || isset($tempIndices['publico']))) { $indices = $tempIndices; }
                    continue; 
                }

                $descIndex = $indices['descripcion'] ?? -1;
                $descripcion = ($descIndex !== -1 && isset($valores[$descIndex])) ? $valores[$descIndex] : '';
                if (empty(trim((string)$descripcion))) continue; 

                $catIndex = $indices['categoria'] ?? -1; $mayIndex = $indices['mayoreo'] ?? -1; $pubIndex = $indices['publico'] ?? -1;
                $categoria = ($catIndex !== -1 && isset($valores[$catIndex])) ? $valores[$catIndex] : 'Llanta';
                $precioMay = ($mayIndex !== -1 && isset($valores[$mayIndex])) ? $valores[$mayIndex] : 0;
                $precioPub = ($pubIndex !== -1 && isset($valores[$pubIndex])) ? $valores[$pubIndex] : 0;
                
                $stockRaw = null;
                if (isset($indices['stock']) && trim((string)($valores[$indices['stock']] ?? '')) !== '') {
                    $stockRaw = $valores[$indices['stock']];
                } else {
                    $valoresAlReves = array_reverse($valores);
                    foreach ($valoresAlReves as $val) {
                        $limpio = trim((string)$val);
                        if ($limpio !== '' && is_numeric(preg_replace('/[^0-9\.]/', '', $limpio))) { $stockRaw = $limpio; break; }
                    }
                }

                $precioMay = (float) preg_replace('/[^0-9\.]/', '', (string)$precioMay);
                $precioPub = (float) preg_replace('/[^0-9\.]/', '', (string)$precioPub);
                $stockLimpio = preg_replace('/[^0-9\.]/', '', (string)$stockRaw);
                $stock = (int) round((float)($stockLimpio ?: 0));

                $medida = 'S/M'; $marca = trim((string)$descripcion);
                $partes = explode(' ', $marca, 2);
                if (count($partes) > 1 && preg_match('/[0-9]/', $partes[0])) { $medida = $partes[0]; $marca = $partes[1]; }

                $producto = Producto::create([
                    'tipo' => mb_substr((string)$categoria, 0, 50),
                    'marca' => mb_strtoupper(mb_substr((string)$marca, 0, 100)),
                    'medida' => mb_strtoupper(mb_substr((string)$medida, 0, 100)),
                    'descripcion' => mb_substr((string)$descripcion, 0, 255),
                    'costo' => $precioMay, 'precio_mayoreo' => $precioMay, 'precio_publico' => $precioPub, 'estado' => true
                ]);

                // Asigna el stock a la sucursal del operador que hizo la subida
                StockSucursal::create(['producto_id' => $producto->id, 'sucursal_id' => $sucursal_destino, 'cantidad' => $stock, 'stock_minimo' => 5]);
                $productosProcesados++;
            }
            DB::commit();
            if ($productosProcesados === 0) { return back()->with('error', 'No detectamos productos válidos.'); }
            return redirect()->route('inventario.index')->with('success', "¡Éxito! Se importaron $productosProcesados productos en la sucursal correspondiente.");
        } catch (\Exception $e) { 
            DB::rollBack(); 
            return back()->with('error', 'Error: ' . $e->getMessage()); 
        }
    }

    public function storeProducto(Request $request)
    {
        $request->validate(['tipo' => 'required|string', 'marca' => 'required|string|max:100', 'medida' => 'required|string|max:100']);
        Producto::create(['tipo' => $request->tipo, 'marca' => mb_strtoupper($request->marca), 'medida' => mb_strtoupper($request->medida), 'descripcion' => $request->descripcion, 'costo' => 0, 'precio_mayoreo' => 0, 'precio_publico' => 0, 'estado' => true]);
        return redirect()->route('inventario.index')->with('success', 'Producto agregado en el catálogo general.');
    }

    public function storeEntrada(Request $request)
    {
        $request->validate(['producto_id' => 'required|exists:productos,id', 'cantidad' => 'required|integer|min:1', 'costo_unitario' => 'required|numeric|min:0']);
        
        try {
            DB::beginTransaction();

            $empleado = DB::table('usuarios')->where('id', Auth::id())->first();
            $sucursalUsuario = $empleado ? $empleado->sucursal_id : 1;
            $sucursal_destino = $request->input('sucursal_id', $sucursalUsuario);

            $producto = Producto::findOrFail($request->producto_id);
            $producto->update(['costo' => $request->costo_unitario]);
            
            // La mercancía entra a la bodega del cajero que la registró
            $stock = StockSucursal::firstOrCreate(
                ['producto_id' => $producto->id, 'sucursal_id' => $sucursal_destino], 
                ['cantidad' => 0, 'stock_minimo' => 5]
            );
            $stock->increment('cantidad', $request->cantidad);
            
            $subtotal_compra = $request->cantidad * $request->costo_unitario;
            CuentaPagar::create(['proveedor_id' => 1, 'concepto' => "Entrada de lote: {$request->cantidad} pzas (Sucursal {$sucursal_destino})", 'pago_ordinario' => $subtotal_compra, 'interes' => 0, 'tipo' => 'cargo', 'fecha_movimiento' => now()]);
            
            DB::commit();
            return redirect()->route('inventario.index')->with('success', "Entrada registrada en tu bodega exitosamente.");
        } catch (\Exception $e) { 
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Error al procesar la entrada.'); 
        }
    }
}