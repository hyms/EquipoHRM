<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasosDiciplinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casos_diciplina', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empleado_id');
            $table->string('nombre');
            $table->text('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('casos_diciplina_history', function (Blueprint $table) {
            $table->unsignedInteger('empleado_id');
            $table->string('nombre');
            $table->text('descripcion');
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
        Schema::dropIfExists('casos_diciplina');
        Schema::dropIfExists('casos_diciplina_history');
    }
}
