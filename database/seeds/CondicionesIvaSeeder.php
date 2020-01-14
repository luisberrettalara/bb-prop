<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Facturacion\CondicionIva;

class CondicionesIvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CondicionIva::create([ 'nombre' => 'Consumidor Final' ]);
        CondicionIva::create([ 'nombre' => 'Responsable Inscripto' ]);
        CondicionIva::create([ 'nombre' => 'Responsable Monotributo' ]);
        CondicionIva::create([ 'nombre' => 'IVA Exento' ]);
    }
}
