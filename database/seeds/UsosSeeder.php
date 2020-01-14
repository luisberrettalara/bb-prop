<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Propiedades\Uso;

class UsosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Uso::create([ 'nombre' => 'Vivienda' ]);
        Uso::create([ 'nombre' => 'Comercial' ]);
    }
}
