<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Propiedades\tiposPropiedad;

class CaracteristicasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Caracteristica::create([ 'nombre' => 'Superficie Cubierta', 'es_servicio' => '0', 'unidad' => 'm2', 'tipo_caracteristica_id' => '1' ]);
        Caracteristica::create([ 'nombre' => 'Expensas Aproximadas', 'es_servicio' => '0', 'unidad' => '$', 'tipo_caracteristica_id' => '1' ]);
        Caracteristica::create([ 'nombre' => 'Ambientes', 'es_servicio' => '0', 'unidad' => '', 'tipo_caracteristica_id' => '1' ]);
        Caracteristica::create([ 'nombre' => 'Balcón', 'es_servicio' => '0', 'unidad' => '', 'tipo_caracteristica_id' => '3' ]);
        Caracteristica::create([ 'nombre' => 'Calefacción Central', 'es_servicio' => '0', 'unidad' => '', 'tipo_caracteristica_id' => '3' ]);
        Caracteristica::create([ 'nombre' => 'Aire Acondicionado', 'es_servicio' => '0', 'unidad' => '', 'tipo_caracteristica_id' => '3' ]);
        Caracteristica::create([ 'nombre' => 'Lavadero Público', 'es_servicio' => '1', 'unidad' => '', 'tipo_caracteristica_id' => '3' ]);
        Caracteristica::create([ 'nombre' => 'Salón de usos Múltiples', 'es_servicio' => '1', 'unidad' => '', 'tipo_caracteristica_id' => '3' ]);
    }
}
