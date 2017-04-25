<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesistenciaMotivosTable extends Migration
{
    /**
     * Run the migrations.
     * @table desistencia_motivos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desistencia_motivos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('desistencia_motivos');
     }
}
