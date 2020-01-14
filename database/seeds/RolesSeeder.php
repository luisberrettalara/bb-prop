<?php

use Illuminate\Database\Seeder;
use Inmuebles\Models\Usuarios\Rol;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create(['id' => '1', 'nombre' => 'Administrador']);
        Rol::create(['id' => '2', 'nombre' => 'Anunciante']);
        Rol::create(['id' => '3', 'nombre' => 'Usuario']);
    }
}
