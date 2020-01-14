<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Comun\Provincia;

class ProvinciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provincia::create(['id' => '1',  'nombre'=>'Buenos Aires']);
        Provincia::create(['id' => '2',  'nombre'=>'Córdoba']);
        Provincia::create(['id' => '3',  'nombre'=>'Santa Fe']);
        Provincia::create(['id' => '4',  'nombre'=>'Catamarca']);
        Provincia::create(['id' => '5',  'nombre'=>'Chaco']);
        Provincia::create(['id' => '6',  'nombre'=>'Chubut']);
        Provincia::create(['id' => '7',  'nombre'=>'Corrientes']);
        Provincia::create(['id' => '8',  'nombre'=>'Entre Ríos']);
        Provincia::create(['id' => '9',  'nombre'=>'Formosa']);
        Provincia::create(['id' => '10', 'nombre'=>'Jujuy']);
        Provincia::create(['id' => '11', 'nombre'=>'La Pampa']);
        Provincia::create(['id' => '12', 'nombre'=>'La Rioja']);
        Provincia::create(['id' => '13', 'nombre'=>'Mendoza']);
        Provincia::create(['id' => '14', 'nombre'=>'Misiones']);
        Provincia::create(['id' => '15', 'nombre'=>'Neuquén']);
        Provincia::create(['id' => '16', 'nombre'=>'Río Negro']);
        Provincia::create(['id' => '17', 'nombre'=>'Salta']);
        Provincia::create(['id' => '18', 'nombre'=>'San Juan']);
        Provincia::create(['id' => '19', 'nombre'=>'Santa Cruz']);
        Provincia::create(['id' => '20', 'nombre'=>'Santiago del Estero']);
        Provincia::create(['id' => '21', 'nombre'=>'Tierra del Fuego']);
        Provincia::create(['id' => '22', 'nombre'=>'Tucumán']);
    }
}
