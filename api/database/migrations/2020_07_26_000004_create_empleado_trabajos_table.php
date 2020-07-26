<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoTrabajosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_trabajos', function (Blueprint $table) {
            $table->id();
            $table->string('empresa');
            $table->string('cargo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->text('comentario')->nullable();
            $table->unsignedInteger('empleado_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('empleado_trabajos_history', function (Blueprint $table) {
            $table->string('empresa');
            $table->string('cargo');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->text('comentario')->nullable();
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
        Schema::dropIfExists('empleado_trabajos_history');
        Schema::dropIfExists('empleado_trabajos');
    }
}
