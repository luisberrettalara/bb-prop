<?php

use Illuminate\Database\Seeder;

use Inmuebles\Models\Pedidos\Estado;

class EstadosPedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::create([ 'nombre' => 'Creado', 'slug' => 'creado' ]);
        Estado::create([ 'nombre' => 'Aprobado', 'slug' => 'aprobado' ]);
        Estado::create([ 'nombre' => 'Pendiente de Acreditación', 'slug' => 'pendiente-de-acreditacion' ]);
        Estado::create([ 'nombre' => 'Rechazado', 'slug' => 'rechazado' ]);
        Estado::create([ 'nombre' => 'En proceso de pago', 'slug' => 'en-proceso' ]);
        Estado::create([ 'nombre' => 'Reintegrado', 'slug' => 'reintegrado' ]);
        Estado::create([ 'nombre' => 'Cancelado', 'slug' => 'cancelado' ]);
        Estado::create([ 'nombre' => 'En disputa', 'slug' => 'en-disputa' ]);
    }
}
