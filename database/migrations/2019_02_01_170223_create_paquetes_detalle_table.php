<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaquetesDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquetes_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad')->unsigned()->default(1);
            $table->integer('paquete_id')->unsigned();
            $table->integer('tipo_credito_id')->unsigned();
            $table->timestamps();

            $table->foreign('paquete_id')->references('id')->on('paquetes');
            $table->foreign('tipo_credito_id')->references('id')->on('tipos_credito');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paquetes_detalle');
    }
}
