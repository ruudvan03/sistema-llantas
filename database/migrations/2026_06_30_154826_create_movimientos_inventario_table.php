<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->enum('tipo', ['entrada', 'salida']);
            $table->integer('cantidad');
            $table->string('motivo', 100)->nullable();
            $table->decimal('costo_unitario', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();

            $table->index('producto_id');
            $table->index('sucursal_id');
            $table->index('tipo');
            $table->index('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};