<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string("nombre",100);

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
        });

        Schema::create('rolesHistory', function (Blueprint $table) {
            $table->string("nombre",100);
            $table->smallInteger("estado");
            $table->boolean("borrado");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });


        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            //$table->string("email")->unique();
            //$table->string("email_verified_at");
            $table->string("password");
            $table->string("alias",20)->nullable();
            $table->text("detail")->default("");
            $table->string('api_token');
            $table->rememberToken();

            //control
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->timestamps();
            $table->foreignId('rol')->constrained('roles');
        });

        Schema::create('usuariosHistory', function (Blueprint $table) {
            $table->string("name");
            $table->string("password");
            $table->string("alias",20)->nullable();
            $table->text("detalle")->default("");
            $table->rememberToken();
            $table->smallInteger("estado");
            $table->boolean("borrado");
            $table->unsignedInteger('rol');
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });


        Schema::create('modulo', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->text("detalle")->default("");

            //control
            $table->smallInteger("estado");
            $table->timestamps();
        });

        Schema::create('moduloHistory', function (Blueprint $table) {
            $table->string("name");
            $table->string("detalle");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy')->nullable();
        });

        Schema::create('permisoModulo', function (Blueprint $table) {
            $table->unsignedInteger('modulo')->nullable();
            $table->unsignedInteger('usuario')->nullable();
            $table->unsignedInteger('rol');
        });

        Schema::create('permisoModuloHistory', function (Blueprint $table) {
            $table->id();
            $table->boolean("borrado");
            $table->unsignedInteger('modulo')->nullable();
            $table->unsignedInteger('usuario')->nullable();
            $table->unsignedInteger('rol');
            //control de historial
            $table->dateTime('registerUtc');
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
        Schema::dropIfExists('permisoModuloHistory');
        Schema::dropIfExists('permisoModulo');

        Schema::dropIfExists('moduloHistory');
        Schema::dropIfExists('modulo');

        Schema::dropIfExists('usuariosHistory');
        Schema::dropIfExists('usuarios');

        Schema::dropIfExists('rolesHistory');
        Schema::dropIfExists('roles');

    }
}
