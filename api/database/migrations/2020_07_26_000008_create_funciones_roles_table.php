<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionesRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funciones_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('funcion_id'); // 0listar, 1crear, 2editar,3eliminar
            $table->unsignedInteger('rol_id'); // 0listar, 1crear, 2editar,3eliminar
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
        Schema::drop('funciones_roles');
    }
}
