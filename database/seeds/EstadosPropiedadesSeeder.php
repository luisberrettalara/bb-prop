<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Propiedades\Estado;

class EstadosPropiedadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::create([ 'nombre' => 'Creada' ]);
        Estado::create([ 'nombre' => 'Publicada' ]);
        Estado::create([ 'nombre' => 'Pausada' ]);
        Estado::create([ 'nombre' => 'Finalizada' ]);
        Estado::create([ 'nombre' => 'Baja' ]);
    }
}
