<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacacionesTipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacaciones_tipo', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->decimal('tiempo_dias');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vacaciones_tipo_history', function (Blueprint $table) {
            $table->string('tipo');
            $table->decimal('tiempo_dias');
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
        Schema::dropIfExists('vacaciones_tipo_history');
        Schema::dropIfExists('vacaciones_tipo');
    }
}
