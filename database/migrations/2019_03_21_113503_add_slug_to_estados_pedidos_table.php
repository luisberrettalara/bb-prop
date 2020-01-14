<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToEstadosPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estados_pedidos', function (Blueprint $table) {
            $table->string('slug')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estados_pedidos', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
