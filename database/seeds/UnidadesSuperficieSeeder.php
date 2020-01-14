<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Propiedades\UnidadSuperficie;

class UnidadesSuperficieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnidadSuperficie::create(['nombre' => 'm2']);
        UnidadSuperficie::create(['nombre' => 'ha']);
    }
}
