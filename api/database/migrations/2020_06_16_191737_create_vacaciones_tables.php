<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacacionesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiposPermisoVacacion', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",100);
            $table->text("detalle");

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('tiposPermisoVacacionHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
//control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('permisosVacacion', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->boolean("tiempo_parcial");
            $table->boolean("tiempo_completo");
            $table->unsignedDecimal("numero_dias");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("observaciones");

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();

            $table->foreignId('personal')->constrained('personal');
            $table->foreignId('tipo_permiso')->constrained('tiposPermisoVacacion');
        });

        Schema::create('permisosVacacionHistory', function (Blueprint $table) {
            $table->boolean("tiempo_parcial");
            $table->boolean("tiempo_completo");
            $table->unsignedDecimal("numero_dias");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("observaciones");
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->unsignedInteger('personal');
            $table->unsignedInteger('tipo_permiso');
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

        Schema::dropIfExists('permisosVacacionHistory');
        Schema::dropIfExists('permisosVacacion');

        Schema::dropIfExists('tiposPermisoVacacionHistory');
        Schema::dropIfExists('tiposPermisoVacacion');


    }
}
