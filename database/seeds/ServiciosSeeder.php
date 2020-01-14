<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Propiedades\Servicio;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Servicio::create([ 'nombre' => 'Permite mascotas' ]);
        Servicio::create([ 'nombre' => 'Permite niños' ]);
        Servicio::create([ 'nombre' => 'Con pileta' ]);
        Servicio::create([ 'nombre' => 'Con jardín' ]);
        Servicio::create([ 'nombre' => 'Con cochera' ]);
        Servicio::create([ 'nombre' => 'Con balcón' ]);
    }
}
