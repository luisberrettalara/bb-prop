<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('codigo', 8)->unique();
            $table->integer('usuario_id')->unsigned();
            $table->string('pago_id')->nullable();
            $table->string('init_point')->nullable();
            
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_usuario_id_foreign');
            $table->dropColumn([ 'codigo', 'usuario_id', 'pago_id', 'init_point' ]);
        });
    }
}
