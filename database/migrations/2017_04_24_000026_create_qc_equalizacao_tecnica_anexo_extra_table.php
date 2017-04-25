<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcEqualizacaoTecnicaAnexoExtraTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_equalizacao_tecnica_anexo_extra
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_equalizacao_tecnica_anexo_extra', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('quadro_de_concorrencia_id');
            $table->string('arquivo', 255);
            $table->string('nome', 255)->nullable();
            $table->timestamps();


            $table->foreign('quadro_de_concorrencia_id', 'qc_eq_tec_qc_fk')
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
       Schema::dropIfExists('qc_equalizacao_tecnica_anexo_extra');
     }
}
