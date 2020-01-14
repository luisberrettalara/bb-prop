<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Propiedades\Operacion;

class OperacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Operacion::create([ 'nombre' => 'Alquiler' ]);
        Operacion::create([ 'nombre' => 'Alquiler Temporario' ]);
        Operacion::create([ 'nombre' => 'Venta' ]);
    }
}
