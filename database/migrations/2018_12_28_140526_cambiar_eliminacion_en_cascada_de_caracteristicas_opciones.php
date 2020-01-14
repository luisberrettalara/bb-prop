<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarEliminacionEnCascadaDeCaracteristicasOpciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caracteristicas_opciones', function (Blueprint $table) {
                $table->dropForeign('caracteristicas_opciones_caracteristica_id_foreign');
                $table->foreign('caracteristica_id')->references('id')->on('caracteristicas')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caracteristicas_opciones', function (Blueprint $table) {
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
        });    
    }
}
