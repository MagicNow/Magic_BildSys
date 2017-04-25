<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEqualizacaoTecnicaAnexosTable extends Migration
{
    /**
     * Run the migrations.
     * @table equalizacao_tecnica_anexos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equalizacao_tecnica_anexos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('tipo_equalizacao_tecnica_id');
            $table->string('arquivo', 255);
            $table->string('nome', 255)->nullable();
            $table->timestamps();


            $table->foreign('tipo_equalizacao_tecnica_id')
                ->references('id')->on('tipo_equalizacao_tecnicas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('equalizacao_tecnica_anexos');
     }
}
