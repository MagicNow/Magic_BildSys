<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEqualizacaoTecnicaItensTable extends Migration
{
    /**
     * Run the migrations.
     * @table equalizacao_tecnica_itens
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equalizacao_tecnica_itens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('tipo_equalizacao_tecnica_id');
            $table->string('nome', 255);
            $table->text('descricao')->nullable();
            $table->tinyInteger('obrigatorio')->nullable()->default('0');
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
       Schema::dropIfExists('equalizacao_tecnica_itens');
     }
}
