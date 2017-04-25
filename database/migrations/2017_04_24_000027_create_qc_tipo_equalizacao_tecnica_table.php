<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcTipoEqualizacaoTecnicaTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_tipo_equalizacao_tecnica
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tipo_equalizacao_tecnica', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('quadro_de_concorrencia_id');
            $table->unsignedInteger('tipo_equalizacao_tecnica_id');
            $table->timestamps();


            $table->foreign('quadro_de_concorrencia_id')
                ->references('id')->on('quadro_de_concorrencias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
       Schema::dropIfExists('qc_tipo_equalizacao_tecnica');
     }
}
