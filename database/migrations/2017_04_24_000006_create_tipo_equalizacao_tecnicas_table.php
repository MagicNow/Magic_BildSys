<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoEqualizacaoTecnicasTable extends Migration
{
    /**
     * Run the migrations.
     * @table tipo_equalizacao_tecnicas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_equalizacao_tecnicas', function (Blueprint $table) {
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
       Schema::dropIfExists('tipo_equalizacao_tecnicas');
     }
}
