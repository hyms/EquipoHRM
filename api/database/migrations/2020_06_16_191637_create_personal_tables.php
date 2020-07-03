<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("apellidos",100);
            $table->string("nombres",100);
            $table->string("ci",15);
            $table->string("telefono_propio",15);
            $table->string("telefono_referencia",15)->nullable();
            $table->date("fecha_nacimiento");
            $table->date("fecha_ingreso");
            $table->string("profesion",200);
            $table->text("Direccion");

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();

            $table->unsignedInteger('areaTrabajo')->nullable();
            $table->unsignedInteger('cargo')->nullable();
            $table->unsignedInteger('unidadNegocio')->nullable();
        });

        Schema::create('personalHistory', function (Blueprint $table) {
            $table->string("apellidos", 100);
            $table->string("nombres", 100);
            $table->string("ci", 15);
            $table->string("telefono_propio", 15);
            $table->string("telefono_referencia", 15)->nullable();
            $table->date("fecha_nacimiento");
            $table->date("fecha_ingreso");
            $table->string("profesion", 200);
            $table->text("Direccion");
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->unsignedInteger('unidad');
            $table->unsignedInteger('cargo');
            $table->unsignedInteger('agencia');
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

        Schema::dropIfExists('personalHistory');
        Schema::dropIfExists('personal');

    }
}
