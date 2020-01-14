<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Comun\Localidad;

class LocalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        Localidad::create(['id'=>'1','nombre'=>'Bahia Blanca','provincia_id'=>'1']);
        Localidad::create(['id'=>'2','nombre'=>'Buenos Aires','provincia_id'=>'1']);
        Localidad::create(['id'=>'3','nombre'=>'Cordoba','provincia_id'=>'2']);
        Localidad::create(['id'=>'4','nombre'=>'Rosario','provincia_id'=>'3']);
    }
}
