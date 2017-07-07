<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeItemModificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('se_item_modificacoes', function($table) {
            $table->increments('id');
            $table->unsignedInteger('solicitacao_entrega_item_id');
            $table->decimal('qtd_anterior', 19, 2);
            $table->decimal('qtd_atual', 19, 2);
            $table->decimal('valor_unitario_anterior', 19, 2);
            $table->decimal('valor_unitario_atual', 19, 2);

            $table->foreign('solicitacao_entrega_item_id')
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
        Schema::dropIfExists('se_item_modificacoes');
    }
}
