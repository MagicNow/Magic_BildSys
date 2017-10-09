<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNfSeItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nf_se_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('nota_fiscal_item_id');
            $table->unsignedInteger('solicitacao_entrega_item_id');

            $table->foreign('nota_fiscal_item_id', 'fk_nota_fiscal_itens_solicitacoes_entrega_itens_idx')
                ->references('id')
                ->on('nota_fiscal_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('solicitacao_entrega_item_id', 'fk_solicitacoes_entrega_itens_nota_fiscal_itens_idx')
                ->references('id')
                ->on('solicitacao_entrega_itens')
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
        Schema::table('', function(Blueprint $table){
            $table->dropForeign('fk_nota_fiscal_itens_solicitacoes_entrega_itens_idx');
            $table->dropForeign('fk_solicitacoes_entrega_itens_nota_fiscal_itens_idx');
        });

        Schema::drop('nf_se_item');
    }
}
