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
        Schema::create('diasFestivos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",200);
            $table->date("fecha");
            //$table->decimal("dias");

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('diasFestivosHistory', function (Blueprint $table) {
            $table->string("nombre",200);
            $table->date("fecha");
            $table->smallInteger("estado");
            $table->boolean("borrado");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->string("nit",20);
            $table->string("direccion",200);
            $table->string("telefono",15);
            $table->string("celular",15);
            $table->string("fax",15)->nullable();
            $table->string("ciudad",50);
            $table->string("departamento",50);
            $table->string("gerente",100)->nullable();
            $table->string("email",100)->nullable();
            $table->string("web",100)->nullable();
            $table->date("fecha_nacimiento");

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('empresasHistory', function (Blueprint $table) {
            $table->string("nombre",100);
            $table->string("nit",20);
            $table->string("direccion",200);
            $table->string("telefono",15);
            $table->string("celular",15);
            $table->string("fax",15)->nullable();
            $table->string("ciudad",50);
            $table->string("departamento",50);
            $table->string("gerente",100)->nullable();
            $table->string("email",100)->nullable();
            $table->string("web",100)->nullable();
            $table->date("fecha_nacimiento");
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
        });

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

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();

            $table->foreignId('id_empresa')->constrained('empresas');
        });

        Schema::create('unidadesNegocioHistory', function (Blueprint $table) {
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
            $table->smallInteger("estado");
            $table->boolean("borrado");
            $table->unsignedInteger('id_empresa');
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('areasTrabajo', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->text("detalle");

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('areasTrabajoHistory', function (Blueprint $table) {
            $table->string("nombre",100);
            $table->text("detalle");
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
//control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);
            $table->text("detalle");
            $table->unsignedInteger("cargo_padre")->nullable();

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('cargosHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->unsignedInteger("cargo_padre")->nullable();
            $table->smallInteger("estado");
            $table->boolean("borrado");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('regional', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 100);
            $table->text("detalle");
            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('regionalHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->smallInteger("estado");
            $table->boolean("borrado");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });
        Schema::create('gerencia', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 100);
            $table->text("detalle");
            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('gerenciaHistory', function (Blueprint $table) {
            $table->string("nombre", 100);
            $table->text("detalle");
            $table->smallInteger("estado");
            $table->boolean("borrado");
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

        Schema::dropIfExists('regionalHistory');
        Schema::dropIfExists('regional');

        Schema::dropIfExists('cargosHistory');
        Schema::dropIfExists('cargos');

        Schema::dropIfExists('areasTrabajoHistory');
        Schema::dropIfExists('areasTrabajo');

        Schema::dropIfExists('unidadesNegocioHistory');
        Schema::dropIfExists('unidadesNegocio');

        Schema::dropIfExists('empresasHistory');
        Schema::dropIfExists('empresas');

        Schema::dropIfExists('diasFestivosHistory');
        Schema::dropIfExists('diasFestivos');

    }
}
