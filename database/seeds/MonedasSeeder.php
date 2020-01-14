<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Propiedades\Moneda;

class MonedasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Moneda::create([ 'nombre' => '$' ]);
        Moneda::create([ 'nombre' => 'USD' ]);
    }
}
