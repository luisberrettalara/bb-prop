<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EliminarColumnaTipoCaracteristicaIdTablaCaracteristicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->dropForeign(['tipo_caracteristica_id']);
            $table->dropColumn('tipo_caracteristica_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caracteristicas', function (Blueprint $table) {
            $table->integer('tipo_caracteristica_id')->unsigned();
            $table->foreign('tipo_caracteristica_id')->references('id')->on('caracteristicas');
        });
    }
}
