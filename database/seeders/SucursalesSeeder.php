<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalesSeeder extends Seeder
{
    public function run()
    {
        DB::table('sucursales')->insert([
            ['id' => 1, 'nombre' => 'Administración General', 'activa' => true],
            ['id' => 2, 'nombre' => 'Chalco', 'activa' => true],
            ['id' => 3, 'nombre' => 'Atlanta', 'activa' => true],
            ['id' => 4, 'nombre' => 'Las Torres', 'activa' => true],
            ['id' => 5, 'nombre' => 'Valle de Chalco', 'activa' => true],
        ]);
    }
}