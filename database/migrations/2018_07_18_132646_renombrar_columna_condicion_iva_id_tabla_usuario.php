<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenombrarColumnaCondicionIvaIdTablaUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('condicion_iva')->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('condicion_iva', 'condicion_iva_id');
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
            $table->renameColumn('condicion_iva_id', 'condicion_iva');
        });
        
        Schema::table('users', function (Blueprint $table) {        
            $table->string('condicion_iva')->change();
        });
    }
}