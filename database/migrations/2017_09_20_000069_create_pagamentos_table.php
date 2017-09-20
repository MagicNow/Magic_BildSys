<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeApropriacaoTable extends Migration
{
    /**
     * Run the migrations.
     * @table se_apropriacao
     *
     * @return void
     */
    public function up()
    {
        Schema::create('se_apropriacao', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('solicitacao_entrega_item_id');
            $table->unsignedInteger('contrato_item_apropriacao_id');
            $table->decimal('qtd', 19, 2);


            $table->foreign('solicitacao_entrega_item_id', 'fk_solicitacao_entrega_itens_has_contrato_item_apropriacoes_idx1')
                ->references('id')->on('solicitacao_entrega_itens')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('contrato_item_apropriacao_id', 'fk_solicitacao_entrega_itens_has_contrato_item_apropriacoes_idx')
                ->references('id')->on('contrato_item_apropriacoes')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('se_apropriacao');
     }
}
