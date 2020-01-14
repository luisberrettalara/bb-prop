<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModificarColumnaCaracteristicasIdTablaTiposPropiedadCaracteristicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_propiedad_caracteristicas', function (Blueprint $table) {
                $table->renameColumn('caracteristicas_id', 'caracteristica_id');
            });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_propiedad_caracteristicas', function (Blueprint $table) {
                $table->integer('caracteristicas_id');
            });
    }
}
