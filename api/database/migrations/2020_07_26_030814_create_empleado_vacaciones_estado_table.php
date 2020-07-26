<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoVacacionesEstadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_vacaciones_estado', function (Blueprint $table) {
            $table->id();
            $table->integer('total_disponible');
            $table->integer('total_usado');
            $table->unsignedInteger('empleado_id');
            $table->timestamps();
        });

        Schema::create('empleado_vacaciones_estado_history', function (Blueprint $table) {
            $table->integer('total_disponible');
            $table->integer('total_usado');
            $table->unsignedInteger('empleado_id');
            //control de historial
            $table->unsignedInteger('id');
            $table->timestamp('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleado_vacaciones_estado_history');
        Schema::dropIfExists('empleado_vacaciones_estado');
    }
}
