<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarCamposAPropiedades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propiedades', function (Blueprint $table) {
            $table->decimal('superficieDescubierta', 8, 2)->nullable();
            $table->decimal('expensas', 8, 2)->nullable();
            $table->integer('tipologia_id')->unsigned()->nullable();
            $table->foreign('tipologia_id')->references('id')->on('tipologias');
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
            $table->dropColumn('superficieDescubierta');
            $table->dropColumn('expensas');
            $table->dropColumn('tipologia_id');
        }); 
    }
}
