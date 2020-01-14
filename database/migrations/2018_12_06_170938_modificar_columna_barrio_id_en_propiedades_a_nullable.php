<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModificarColumnaBarrioIdEnPropiedadesANullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propiedades', function (Blueprint $table) {
                $table->dropForeign('propiedades_barrio_id_foreign');
                $table->integer('barrio_id')->unsigned()->nullable()->change();
                $table->foreign('barrio_id')->references('id')->on('barrios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propiedades', function (Blueprint $table) {
               $table->foreign('barrio_id')->references('id')->on('barrios'); 
        });
    }
}
