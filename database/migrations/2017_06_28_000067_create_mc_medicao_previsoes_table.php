<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcMedicaoPrevisoesTable extends Migration
{
    /**
     * Run the migrations.
     * @table mc_medicao_previsoes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mc_medicao_previsoes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('obra_torre_id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('memoria_calculo_bloco_id');
            $table->unsignedInteger('contrato_item_apropriacao_id');
            $table->unsignedInteger('contrato_item_id');
            $table->unsignedInteger('planejamento_id');
            $table->decimal('qtd', 19, 2)->nullable();
            $table->string('unidade_sigla', 5);
            $table->unsignedInteger('user_id');
            $table->date('data_competencia');
            $table->timestamps();


            $table->foreign('memoria_calculo_bloco_id')
                ->references('id')->on('memoria_calculo_blocos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('contrato_item_apropriacao_id')
                ->references('id')->on('contrato_item_apropriacoes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('contrato_item_id')
                ->references('id')->on('contrato_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('obra_torre_id')
                ->references('id')->on('obra_torres')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('unidade_sigla')
                ->references('sigla')->on('unidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
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
       Schema::dropIfExists('mc_medicao_previsoes');
     }
}
