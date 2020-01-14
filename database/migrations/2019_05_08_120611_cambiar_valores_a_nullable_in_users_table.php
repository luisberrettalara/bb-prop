<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambiarValoresANullableInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('razon_social')->nullable()->change();
            $table->string('domicilio')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('cuit')->nullable()->change();
            $table->unsignedInteger('condicion_iva_id')->nullable()->change();
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
            $table->string('razon_social');
            $table->string('domicilio');
            $table->string('telefono');
            $table->string('cuit');
            $table->unsignedInteger('condicion_iva_id');
        });
    }
}
