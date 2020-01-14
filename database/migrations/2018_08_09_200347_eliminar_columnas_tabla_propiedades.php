<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EliminarColumnasTablaPropiedades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propiedades', function (Blueprint $table) {
                $table->dropColumn('cant_ambientes');
                $table->dropColumn('impuestos_aproximados');
                $table->dropColumn('superficie_edificada');
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
            $table->integer('cant_ambientes');
            $table->decimal('impuestos_aproximados',8,2)->unsigned()->default(0);
            $table->unsignedDecimal('superficie_edificada',8,2)->default(0);
        });
    }
}
