<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'activa'
    ];
}