<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Propiedades\TipoCaracteristica;

class TiposCaracteristicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoCaracteristica::create([ 'nombre' => 'Numerico' ]);
        TipoCaracteristica::create([ 'nombre' => 'Texto' ]);
        TipoCaracteristica::create([ 'nombre' => 'Opcion' ]);
        TipoCaracteristica::create([ 'nombre' => 'Dropdown']);
    }
}
