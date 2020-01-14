<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetUniqueOrdenTipoPropiedadIdInTiposPropiedadCaracteristicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_propiedad_caracteristicas', function (Blueprint $table) {
            $table->index(['tipo_propiedad_id', 'orden']);
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
            $table->dropUnique(['tipo_propiedad_id', 'orden']);
        });
    }
}
