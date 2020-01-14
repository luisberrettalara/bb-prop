<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarEliminacionEnCascadaDeTipoPropiedadCaracteristicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_propiedad_caracteristicas', function (Blueprint $table) {
                $table->dropForeign('tipos_propiedad_caracteristicas_caracteristicas_id_foreign');
                $table->foreign('caracteristica_id')->references('id')->on('caracteristicas')->onDelete('cascade')->change();
                $table->dropForeign('tipos_propiedad_caracteristicas_tipo_propiedad_id_foreign');
                $table->foreign('tipo_propiedad_id')->references('id')->on('tipos_propiedad')->onDelete('cascade')->change();
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
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
            $table->foreign('tipo_propiedad_id')->references('id')->on('tipos_propiedad');
        });
    }
}
