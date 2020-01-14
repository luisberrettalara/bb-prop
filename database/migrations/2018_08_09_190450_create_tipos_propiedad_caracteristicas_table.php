<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiposPropiedadCaracteristicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_propiedad_caracteristicas', function (Blueprint $table) {
            $table->integer('tipo_propiedad_id')->unsigned();
            $table->foreign('tipo_propiedad_id')->references('id')->on('tipos_propiedad');
            $table->integer('caracteristicas_id')->unsigned();
            $table->foreign('caracteristicas_id')->references('id')->on('caracteristicas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_propiedad_caracteristicas');
    }
}
