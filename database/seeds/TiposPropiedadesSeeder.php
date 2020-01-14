<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Propiedades\TipoPropiedad;

class TiposPropiedadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPropiedad::create([ 'nombre' => 'Departamento' ]);
        TipoPropiedad::create([ 'nombre' => 'Casa' ]);
        TipoPropiedad::create([ 'nombre' => 'Terreno' ]);
        TipoPropiedad::create([ 'nombre' => 'Emprendimiento de Pozo' ]);
    }
}
