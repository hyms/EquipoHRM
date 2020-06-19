<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ROLES', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",100);

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->smallInteger("secuencia"); //secuencia de uso
            $table->timestamps();
        });

        Schema::create('USUARIOS', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("usuario",20);
            $table->string("contraseña",20);
            $table->string("pregunta_seguridad",200);
            $table->string("alias",20)->nullable();
            $table->text("detalle")->default("");

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->smallInteger("secuencia"); //secuencia de uso
            $table->timestamps();
            $table->foreignId('rol')->constrained('ROLES');
        });

        Schema::create('CONFIGURACION', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",200);
            $table->smallInteger("tipo");
            $table->text("valor");
            $table->smallInteger("identificador");

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });

        Schema::create('DESPLEGABLES', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",200);
            $table->smallInteger("tipo");  //el tipo determinara que desplegable se mostrara o filtrara
            $table->text("valor");
            $table->unsignedInteger("id_padre")->nullable();

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });

        Schema::create('DIAS_FESTIVOS', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",200);
            $table->date("fecha");
            $table->decimal("dias");

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });

        Schema::create('EMPRESAS', function (Blueprint $table) {
            $table->id()->autoIncrement();
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

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });

        Schema::create('AGENCIAS', function (Blueprint $table) {
            $table->id()->autoIncrement();
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

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
            $table->foreignId('id_empresa')->constrained('EMPRESAS');
        });

        Schema::create('UNIDADES', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",100);
            $table->text("detalle");

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario')->constrained('USUARIOS');
        });

        Schema::create('CARGOS', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",100);
            $table->text("detalle");
            $table->text("cargp_padre")->nullable();

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
            $table->foreignId('unidad')->constrained('UNIDADES');
        });

        Schema::create('PERSONAL', function (Blueprint $table) {
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

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
            $table->foreignId('unidad')->constrained('UNIDADES');
            $table->foreignId('cargo')->constrained('CARGOS');
            $table->foreignId('agencia')->constrained('AGENCIAS');
        });

        Schema::create('TIPOS_PERMISO_VACACION', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",100);
            $table->text("detalle");

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });

        Schema::create('PERMISOS', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->boolean("tiempo_parcial");
            $table->boolean("tiempo_completo");
            $table->unsignedDecimal("numero_dias");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("observaciones");

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('usuario_adm')->constrained('USUARIOS');
            $table->foreignId('personal')->constrained('PERSONAL');
            $table->foreignId('tipo_permiso')->constrained('TIPOS_PERMISO_VACACION');
        });

        Schema::create('EQUIPO', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string("nombre",100);
            $table->text("detalle");
            $table->text("observaciones")->nullable();

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->smallInteger("secuencia"); //secuencia de uso
            $table->timestamps();
            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });

        Schema::create('ASIGNACIONES', function (Blueprint $table) {
            $table->id()->autoIncrement();

            //control de historial
            $table->smallInteger("estado");
            $table->boolean("indicador"); //Activo,inactivo
            $table->integer("secuencia"); //secuencia de uso
            $table->timestamps();

            $table->foreignId('recibido_por')->constrained('PERSONAL');
            $table->foreignId('entregado_por')->constrained('PERSONAL');
            $table->foreignId('id_material')->constrained('EQUIPO');
            $table->foreignId('usuario_adm')->constrained('USUARIOS');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ASIGNACIONES');
        Schema::dropIfExists('EQUIPO');
        Schema::dropIfExists('PERMISOS');
        Schema::dropIfExists('TIPOS_PERMISO_VACACION');
        Schema::dropIfExists('PERSONAL');
        Schema::dropIfExists('CARGOS');
        Schema::dropIfExists('UNIDADES');
        Schema::dropIfExists('AGENCIAS');
        Schema::dropIfExists('EMPRESAS');
        Schema::dropIfExists('DIAS_FESTIVOS');
        Schema::dropIfExists('DESPLEGABLES');
        Schema::dropIfExists('CONFIGURACION');
        Schema::dropIfExists('USUARIOS');
        Schema::dropIfExists('ROLES');

    }
}
