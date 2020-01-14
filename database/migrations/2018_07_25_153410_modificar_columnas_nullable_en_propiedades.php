<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModificarColumnasNullableEnPropiedades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propiedades', function (Blueprint $table) {
                $table->integer('cant_ambientes')->nullable()->change();
                $table->decimal('monto',8,2)->nullable()->change();
                $table->decimal('superficie_edificada',8,2)->nullable()->change();
                $table->decimal('impuestos_aproximados',8,2)->nullable()->change();
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
                $table->decimal('monto',8,2);
                $table->decimal('superficie_edificada',8,2);
                $table->decimal('impuestos_aproximados',8,2);
            });
    }
}
