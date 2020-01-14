<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarClavesForaneasAUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->foreign('rol_id')->references('id')->on('roles');
            $table->foreign('condicion_iva_id')->references('id')->on('condiciones_iva');
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('users', function (Blueprint $table) {
            
            $table->dropForeign('users_rol_id_foreign');
            $table->dropForeign('users_condicion_iva_id_foreign');
    });

    }
}
