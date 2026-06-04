<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Producto;

class PuntoVentaController extends Controller
{
    public function index()
    {
        $sucursalId = 1; // TODO: cambiar cuando hagas la unión usuario-sucursal

        $productos = Producto::where('productos.estado', 1)
            ->leftJoin('stock_sucursal', function ($j) use ($sucursalId) {
                $j->on('productos.id', '=', 'stock_sucursal.producto_id')
                  ->where('stock_sucursal.sucursal_id', $sucursalId);
            })
            ->select(
                'productos.*',
                DB::raw('COALESCE(stock_sucursal.cantidad, 0) as stock_cantidad')
            )
            ->orderBy('productos.tipo')
            ->orderBy('productos.marca')
            ->orderBy('productos.medida')
            ->get();

        return view('ventas.index', compact('productos'));
    }
}