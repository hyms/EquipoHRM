<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos_tipo', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permisos_tipo_history', function (Blueprint $table) {
            $table->string('tipo');
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
        Schema::dropIfExists('permisos_tipo_history');
        Schema::dropIfExists('permisos_tipo');
    }
}
