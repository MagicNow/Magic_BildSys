<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcEqualizacaoTecnicaExtrasTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_equalizacao_tecnica_extras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_equalizacao_tecnica_extras', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('quadro_de_concorrencia_id');
            $table->string('nome', 255);
            $table->text('descricao')->nullable();
            $table->tinyInteger('obrigatorio')->nullable()->default('0');
            $table->timestamps();


            $table->foreign('quadro_de_concorrencia_id')
                ->references('id')->on('quadro_de_concorrencias')
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
       Schema::dropIfExists('qc_equalizacao_tecnica_extras');
     }
}
