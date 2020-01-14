<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarClaveForaneaTipoCaracteristicaIdACaracteristicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->integer('tipo_caracteristica_id')->unsigned();
            $table->foreign('tipo_caracteristica_id')->references('id')->on('tipos_caracteristica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         $table->dropForeign('tipo_caracteristica_id');
    }
}
