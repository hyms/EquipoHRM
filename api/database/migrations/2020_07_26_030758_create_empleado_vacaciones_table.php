<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoVacacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empleado_id');
            $table->unsignedInteger('tipo_vacaciones_id');
            $table->unsignedDecimal("numero_dias");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("observaciones");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('empleado_vacaciones_history', function (Blueprint $table) {
            $table->unsignedInteger('empleado_id');
            $table->unsignedInteger('tipo_vacaciones_id');
            $table->unsignedDecimal("numero_dias");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("observaciones");
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
        Schema::dropIfExists('empleado_vacaciones_history');
        Schema::dropIfExists('usuario_vacaciones');
    }
}
