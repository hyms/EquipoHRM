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

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();

        });

        Schema::create('personalHistory', function (Blueprint $table) {
            $table->string("apellidos", 100);
            $table->string("nombres", 100);
            $table->string("ci", 15);
            $table->string("telefono_propio", 15);
            $table->string("telefono_referencia", 15)->nullable();
            $table->date("fecha_nacimiento");
            $table->string("profesion", 200);
            $table->text("Direccion");
            $table->string("estado_civil");
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('carrera', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("personal");
            $table->unsignedInteger("cargo");
            $table->unsignedInteger("unidad_negocio");
            $table->unsignedInteger("area_trabajo");
            $table->unsignedInteger("regional");
            $table->unsignedInteger("gerencia");
            $table->unsignedInteger("usuario")->nullable();
            $table->date("fecha_ingreso");
            $table->timestamps();
        });
        Schema::create('carreraHistory', function (Blueprint $table) {
            $table->unsignedInteger("personal");
            $table->unsignedInteger("cargo");
            $table->unsignedInteger("unidad_negocio");
            $table->unsignedInteger("area_trabajo");
            $table->unsignedInteger("regional");
            $table->unsignedInteger("gerencia");
            $table->unsignedInteger("usuario")->nullable();
            $table->date("fecha_ingreso");
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

        Schema::dropIfExists('carreraHistory');
        Schema::dropIfExists('carrera');

        Schema::dropIfExists('personalHistory');
        Schema::dropIfExists('personal');

    }
}
