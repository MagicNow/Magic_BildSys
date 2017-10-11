<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisicaoAplicacaoEstoqueInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aplicacao_estoque_insumos', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('requisicao_id');
            $table->unsignedInteger('aplicacao_estoque_local_id');
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('insumo_id');

            $table->integer('qtd');
            $table->string('unidade_medida');
            $table->string('pavimento');
            $table->string('andar');
            $table->string('apartamento');
            $table->string('comodo');
            $table->timestamps();
            
            $table->foreign('requisicao_id')
                ->references('id')->on('requisicao')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('aplicacao_estoque_local_id')
                ->references('id')->on('aplicacao_estoque_locais')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
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
        Schema::dropIfExists('aplicacao_estoque_insumos');
    }
}
