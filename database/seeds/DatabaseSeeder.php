<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(RolesSeeder::class);
        // $this->call(OperacionesSeeder::class);
        // $this->call(TiposPropiedadesSeeder::class);
        // $this->call(CondicionesIvaSeeder::class);
        // $this->call(ProvinciasSeeder::class);
        // $this->call(LocalidadesSeeder::class);
        $this->call(BarriosSeeder::class);
        // $this->call(UsosSeeder::class);
        // $this->call(TiposCaracteristicaSeeder::class);
        // $this->call(CaracteristicasSeeder::class);
        // $this->call(MonedasSeeder::class);
        // $this->call(EstadosPropiedadesSeeder::class);
        // $this->call(UnidadesSuperficieSeeder::class);
        // $this->call(EstadosPedidosSeeder::class);
    }
}
