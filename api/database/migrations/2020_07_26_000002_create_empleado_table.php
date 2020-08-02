<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado', function (Blueprint $table) {
            $table->id();
            $table->string("apellidos", 100);
            $table->string("nombres", 100);
            $table->string("ci", 20);
            $table->string("telefono_propio", 15);
            $table->string("telefono_referencia", 15)->nullable();
            $table->date("fecha_nacimiento");
            $table->string("profesion", 200);
            $table->text("direccion");
            $table->string("estado_civil");
            $table->unsignedInteger('id_usuario')->nullable();
            $table->date('fecha_ingreso');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('empleado_history', function (Blueprint $table) {
            $table->string("apellidos", 100);
            $table->string("nombres", 100);
            $table->string("ci", 20);
            $table->string("telefono_propio", 15);
            $table->string("telefono_referencia", 15)->nullable();
            $table->date("fecha_nacimiento");
            $table->string("profesion", 200);
            $table->text("direccion");
            $table->string("estado_civil");
            $table->unsignedInteger('id_usuario')->nullable();
            $table->date('fecha_ingreso');
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
        Schema::dropIfExists('empleado_history');
        Schema::dropIfExists('empleado');
    }
}
