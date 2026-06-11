<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $fillable = [
        'sucursal_id',
        'nombre',
        'usuario',
        'password',
        'rol',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];
}