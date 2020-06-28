<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTablesHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

        Schema::create('configuracionHistory', function (Blueprint $table) {
            $table->string("nombre",200);
            $table->smallInteger("tipo");
            $table->text("valor");
            $table->smallInteger("identificador");
            $table->smallInteger("estado");
            $table->boolean("borrado");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('desplegablesHistory', function (Blueprint $table) {
            $table->string("nombre",200);
            $table->smallInteger("tipo");
            $table->text("valor");
            $table->unsignedInteger("id_padre")->nullable();
            $table->smallInteger("estado");
            $table->boolean("borrado");
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('dias_festivosHistory', function (Blueprint $table) {
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

        Schema::create('unidades_negocioHistory', function (Blueprint $table) {
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

        Schema::create('unidadesHistory', function (Blueprint $table) {
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

        Schema::create('cargosHistory', function (Blueprint $table) {
            $table->string("nombre",100);
            $table->text("detalle");
            $table->text("cargo_padre")->nullable();
$table->smallInteger("estado");
            $table->boolean("borrado");
            $table->unsignedInteger('unidad');
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id','registerUtc']);
            $table->unsignedInteger('registerBy');
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

        Schema::create('tipos_permiso_vacacionHistory', function (Blueprint $table) {
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

        Schema::create('permisosHistory', function (Blueprint $table) {
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

        //modulo de asignacion de material
        Schema::create('equipoHistory', function (Blueprint $table) {
            $table->string("nombre",100);
            $table->text("detalle");
            $table->text("observaciones")->nullable();
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });

        Schema::create('asignacionesHistory', function (Blueprint $table) {
            $table->smallInteger("estado");
            $table->boolean("borrado"); //Activo,inactivo
            $table->unsignedInteger('recibido_por');
            $table->unsignedInteger('entregado_por');
            $table->unsignedInteger('id_material');
            //control de historial
            $table->unsignedInteger('id');
            $table->dateTime('registerUtc');
            $table->primary(['id', 'registerUtc']);
            $table->unsignedInteger('registerBy');
        });
        //modulo
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignacionesHistory');
        Schema::dropIfExists('equipoHistory');
        Schema::dropIfExists('permisosHistory');
        Schema::dropIfExists('tipos_permiso_vacacionHistory');
        Schema::dropIfExists('personalHistory');
        Schema::dropIfExists('cargosHistory');
        Schema::dropIfExists('unidadesHistory');
        Schema::dropIfExists('unidades_negocioHistory');
        Schema::dropIfExists('empresasHistory');
        Schema::dropIfExists('dias_festivosHistory');
        Schema::dropIfExists('desplegablesHistory');
        Schema::dropIfExists('configuracionHistory');
        Schema::dropIfExists('usuariosHistory');
        Schema::dropIfExists('rolesHistory');

    }
}
