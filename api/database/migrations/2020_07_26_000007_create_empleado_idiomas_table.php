<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoIdiomasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_idiomas', function (Blueprint $table) {
            $table->id();
            $table->enum('nivel', ['alto', 'medio', 'bajo']);
            $table->string('idioma');
            $table->unsignedInteger('empleado_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('empleado_idiomas_history', function (Blueprint $table) {
            $table->string('nivel');
            $table->string('idioma');
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
        Schema::dropIfExists('empleado_idiomas_history');
        Schema::dropIfExists('usuario_idiomas');
    }
}
