<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoEmpresaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('empleado_empresa', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("empleado");
            $table->unsignedInteger("cargo");
            $table->unsignedInteger("unidad_negocio");
            $table->unsignedInteger("area_trabajo");
            $table->unsignedInteger("regional");
            $table->unsignedInteger("gerencia");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('empleado_empresa_history', function (Blueprint $table) {
            $table->unsignedInteger("empleado");
            $table->unsignedInteger("cargo");
            $table->unsignedInteger("unidad_negocio");
            $table->unsignedInteger("area_trabajo");
            $table->unsignedInteger("regional");
            $table->unsignedInteger("gerencia");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('empleado_empresa_history');
        Schema::dropIfExists('empleado_empresa');

    }
}
