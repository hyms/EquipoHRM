<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoHabilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_habilidades', function (Blueprint $table) {
            $table->id();
            $table->string('habilidad');
            $table->unsignedInteger('empleado_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('empleado_habilidades_history', function (Blueprint $table) {
            $table->string('habilidad');
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
        Schema::dropIfExists('empleado_habilidades_history');
        Schema::dropIfExists('empleado_habilidades');
    }
}
