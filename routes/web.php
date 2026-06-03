<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AuthController;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/importar', [InventarioController::class, 'importar'])->name('inventario.importar');
    Route::post('/inventario/importar', [InventarioController::class, 'procesarImportacion'])->name('inventario.procesar');
    Route::post('/inventario/producto', [InventarioController::class, 'storeProducto'])->name('inventario.producto.store');
    Route::post('/inventario/entrada', [InventarioController::class, 'storeEntrada'])->name('inventario.entrada.store');


    Route::get('/ventas', function() { return view('ventas.index'); })->name('ventas.index');
    Route::get('/clientes', function() { return view('clientes.index'); })->name('clientes.index');
    Route::get('/reportes', function() { return view('reportes.index'); })->name('reportes.index');
});