<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    
    public $timestamps = false; 

    protected $fillable = [
        'tipo',
        'marca',
        'medida',
        'descripcion',
        'costo',
        'precio_publico',
        'precio_mayoreo',
        'estado'
    ];

    public function stock()
    {
        return $this->hasMany(StockSucursal::class, 'producto_id');
    }
}