<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_permisos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empleado_id');
            $table->unsignedInteger('tipo_permisos_id')->nullable();
            $table->unsignedFloat("tiempo");
            $table->date("fecha");
            $table->text("observaciones");
            $table->unsignedTinyInteger("estado")->default(0);
            $table->unsignedInteger("aprobado_por")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('empleado_permisos_history', function (Blueprint $table) {
            $table->unsignedInteger('empleado_id');
            $table->unsignedInteger('tipo_permisos_id')->nullable();
            $table->unsignedFloat("tiempo");
            $table->date("fecha");
            $table->text("observaciones");
            $table->unsignedTinyInteger("estado")->default(0);
            $table->unsignedInteger("aprobado_por")->nullable();
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
        Schema::dropIfExists('empleado_permisos_history');
        Schema::dropIfExists('empleado_permisos');
    }
}
