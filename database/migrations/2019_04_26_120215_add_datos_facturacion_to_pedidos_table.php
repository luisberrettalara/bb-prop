<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatosFacturacionToPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->integer('punto_venta')->unsigned()->nullable();
            $table->integer('numero_comprobante')->unsigned()->nullable();
            $table->string('cae')->nullable();
            $table->date('fecha_vencimiento_cae')->nullable();
            $table->string('observaciones_cae')->nullable();
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
            $table->dropColumns([ 'punto_venta', 'numero_comprobante', 'cae', 'fecha_vencimiento_cae', 'observaciones_cae' ]);
        });
    }
}
