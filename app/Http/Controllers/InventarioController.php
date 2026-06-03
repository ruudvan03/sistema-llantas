<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\StockSucursal;
use App\Models\Sucursal;
use App\Models\CuentaPagar;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {

        $query = Producto::withSum('stock as stock_cantidad', 'cantidad');

        if ($request->filled('tipo') && $request->tipo !== 'Todos') {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('marca', 'like', '%' . $request->q . '%')
                  ->orWhere('medida', 'like', '%' . $request->q . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->q . '%');
            });
        }

        $productos = $query->orderBy('marca')->orderBy('medida')->paginate(10);
        $sucursales = Sucursal::all();

        return view('inventario.index', compact('productos', 'sucursales'));
    }

    public function importar()
    {
        return view('inventario.importar');
    }

    public function procesarImportacion(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        try {
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
                    
                    if (isset($tempIndices['descripcion']) && (isset($tempIndices['mayoreo']) || isset($tempIndices['publico']))) {
                        $indices = $tempIndices;
                    }
                    continue; 
                }

                $descIndex = $indices['descripcion'] ?? -1;
                $descripcion = ($descIndex !== -1 && isset($valores[$descIndex])) ? $valores[$descIndex] : '';
                
                if (empty(trim((string)$descripcion))) continue; 

                $catIndex = $indices['categoria'] ?? -1;
                $mayIndex = $indices['mayoreo'] ?? -1;
                $pubIndex = $indices['publico'] ?? -1;

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
                        if ($limpio !== '' && is_numeric(preg_replace('/[^0-9\.]/', '', $limpio))) {
                            $stockRaw = $limpio;
                            break;
                        }
                    }
                }

                $precioMay = (float) preg_replace('/[^0-9\.]/', '', (string)$precioMay);
                $precioPub = (float) preg_replace('/[^0-9\.]/', '', (string)$precioPub);
                
                $stockLimpio = preg_replace('/[^0-9\.]/', '', (string)$stockRaw);
                $stock = (int) round((float)($stockLimpio ?: 0));

                $medida = 'S/M';
                $marca = trim((string)$descripcion);
                
                $partes = explode(' ', $marca, 2);
                if (count($partes) > 1 && preg_match('/[0-9]/', $partes[0])) {
                    $medida = $partes[0];
                    $marca = $partes[1];
                }

                $producto = Producto::create([
                    'tipo' => mb_substr((string)$categoria, 0, 50),
                    'marca' => mb_strtoupper(mb_substr((string)$marca, 0, 100)),
                    'medida' => mb_strtoupper(mb_substr((string)$medida, 0, 100)),
                    'descripcion' => mb_substr((string)$descripcion, 0, 255),
                    'costo' => $precioMay, 
                    'precio_mayoreo' => $precioMay,
                    'precio_publico' => $precioPub,
                    'estado' => true
                ]);

                StockSucursal::create([
                    'producto_id' => $producto->id,
                    'sucursal_id' => 1,
                    'cantidad' => $stock,
                    'stock_minimo' => 5
                ]);

                $productosProcesados++;
            }

            DB::commit();

            if ($productosProcesados === 0) {
                return back()->with('error', 'No detectamos productos válidos. Verifica los encabezados de tu Excel.');
            }

            return redirect()->route('inventario.index')->with('success', "¡Éxito! Se importaron $productosProcesados productos con su stock real.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error interno al procesar el archivo: ' . $e->getMessage());
        }
    }

    public function storeProducto(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'marca' => 'required|string|max:100',
            'medida' => 'required|string|max:100',
            'descripcion' => 'nullable|string'
        ]);

        Producto::create([
            'tipo' => $request->tipo,
            'marca' => mb_strtoupper($request->marca),
            'medida' => mb_strtoupper($request->medida),
            'descripcion' => $request->descripcion,
            'costo' => 0,
            'precio_mayoreo' => 0,
            'precio_publico' => 0,
            'estado' => true
        ]);

        return redirect()->route('inventario.index')->with('success', 'Producto agregado exitosamente al catálogo.');
    }

    public function storeEntrada(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'costo_unitario' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $producto = Producto::findOrFail($request->producto_id);
            $producto->update(['costo' => $request->costo_unitario]);

            $stock = StockSucursal::firstOrCreate(
                ['producto_id' => $producto->id, 'sucursal_id' => 1],
                ['cantidad' => 0, 'stock_minimo' => 5]
            );
            $stock->increment('cantidad', $request->cantidad);

            $subtotal_compra = $request->cantidad * $request->costo_unitario;
            
            CuentaPagar::create([
                'proveedor_id' => 1, 
                'concepto' => "Entrada de lote: {$request->cantidad} pzas - {$producto->marca} {$producto->medida}",
                'pago_ordinario' => $subtotal_compra,
                'interes' => 0, 
                'tipo' => 'cargo',
                'fecha_movimiento' => now()
            ]);

            DB::commit();

            return redirect()->route('inventario.index')->with('success', "Entrada registrada. Cargo generado por $" . number_format($subtotal_compra, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la entrada: ' . $e->getMessage());
        }
    }
}