<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('unidadesNegocio', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->string("direccion",200);
            $table->string("telefono",15);
            $table->string("celular",15);
            $table->string("fax",15)->nullable();
            $table->string("ciudad",50);
            $table->string("departamento",50);
            $table->string("encargado",100);
            $table->string("email",100)->nullable();
            $table->string("web",100)->nullable();
            $table->date("fecha_nacimiento");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('unidadesNegocioHistory', function (Blueprint $table) {
            $table->string("nombre",100);
            $table->string("direccion",200);
            $table->string("telefono",15);
            $table->string("celular",15);
            $table->string("fax", 15)->nullable();
            $table->string("ciudad", 50);
            $table->string("departamento", 50);
            $table->string("encargado", 100);
            $table->string("email", 100)->nullable();
            $table->string("web", 100)->nullable();
            $table->date("fecha_nacimiento");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });

        Schema::create('areasTrabajo', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->text("detalle");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('areasTrabajoHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });

        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->text("detalle");
            $table->unsignedInteger("cargo_padre")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cargosHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->unsignedInteger("cargo_padre")->nullable();
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });

        Schema::create('regional', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('regionalHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });
        Schema::create('gerencia', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gerenciaHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
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
        Schema::dropIfExists('gerenciaHistory');
        Schema::dropIfExists('gerencia');

        Schema::dropIfExists('regionalHistory');
        Schema::dropIfExists('regional');

        Schema::dropIfExists('cargosHistory');
        Schema::dropIfExists('cargos');

        Schema::dropIfExists('unidadesNegocioHistory');
        Schema::dropIfExists('unidadesNegocio');

        Schema::dropIfExists('areasTrabajoHistory');
        Schema::dropIfExists('areasTrabajo');
    }
}
