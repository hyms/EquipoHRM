<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiasFestivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_festivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->date('fecha');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('dias_festivos_history', function (Blueprint $table) {
            $table->string('nombre', 150);
            $table->date('fecha');
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
        Schema::dropIfExists('dias_festivos_history');
        Schema::dropIfExists('dias_festivos');
    }
}
