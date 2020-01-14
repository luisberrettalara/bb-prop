<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarEliminacionEnCascadaDeCaracteristicasPropiedad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caracteristicas_propiedad', function (Blueprint $table) {
                $table->dropForeign('caracteristicas_propiedad_caracteristica_id_foreign');
                $table->foreign('caracteristica_id')->references('id')->on('caracteristicas')->onDelete('cascade')->change();
                $table->dropForeign('caracteristicas_propiedad_propiedad_id_foreign');
                $table->foreign('propiedad_id')->references('id')->on('propiedades')->onDelete('cascade')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caracteristicas_propiedad', function (Blueprint $table) {
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
            $table->foreign('propiedad_id')->references('id')->on('propiedades');
        });
    }
}
