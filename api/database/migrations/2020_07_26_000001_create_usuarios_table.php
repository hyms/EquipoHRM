<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string("username")->unique();
            $table->string("password")->nullable();
            $table->string("email")->nullable();
            $table->string("alias", 20)->nullable();
            $table->string('api_token')->nullable();
            $table->boolean("estado");
            $table->tinyInteger("rol");
            $table->date("ultimo_acceso")->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('usuarios_history', function (Blueprint $table) {
            $table->string("username");
            $table->string("password")->nullable();
            $table->string("email")->nullable();
            $table->string("alias", 20)->nullable();
            $table->string('api_token')->nullable();
            $table->boolean("estado");
            $table->tinyInteger("rol");
            $table->date("ultimo_acceso")->nullable();
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
        Schema::dropIfExists('usuarios_history');
        Schema::dropIfExists('usuarios');
    }
}
