<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoEducacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_educacion', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('tipo');
            $table->string('institucion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->unsignedInteger('empleado_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('empleado_educacion_history', function (Blueprint $table) {
            $table->tinyInteger('tipo');
            $table->string('institucion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
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
        Schema::dropIfExists('empleado_educacion_history');
        Schema::dropIfExists('empleado_educacion');
    }
}
