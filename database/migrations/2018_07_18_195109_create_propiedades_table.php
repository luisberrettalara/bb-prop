<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropiedadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propiedades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('direccion');
            $table->string('piso');
            $table->string('departamento');
            $table->integer('barrio_id')->unsigned();
            $table->foreign('barrio_id')->references('id')->on('barrios');
            $table->integer('localidad_id')->unsigned();
            $table->foreign('localidad_id')->references('id')->on('localidades');
            $table->string('lat');
            $table->string('long');
            $table->string('descripcion');
            $table->decimal('superficie',8,2);
            $table->integer('cant_ambientes');
            $table->integer('tipo_propiedad_id')->unsigned();
            $table->foreign('tipo_propiedad_id')->references('id')->on('tipos_propiedad');
            $table->integer('operacion_id')->unsigned();
            $table->foreign('operacion_id')->references('id')->on('operaciones');
            $table->decimal('monto',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propiedades');
    }
}
