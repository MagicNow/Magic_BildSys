<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitacaoEntregaItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacao_entrega_itens', function($table) {
            $table->increments();
            $table->unsignedInteger('solicitacao_entrega_id');
            $table->unsignedInteger('contrato_item_id');
            $table->unsignedInteger('insumo_id');
            $table->decimal('qtd', 19, 2);
            $table->decimal('valor_unitario', 19, 2);
            $table->decimal('valor_total', 19, 2);
            $table->timestamps();

            $table->foreign('solicitacao_entrega_id')
                ->references('id')
                ->on('solicitacao_entregas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('contrato_item_id')
                ->references('id')
                ->on('contrato_itens')
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
        Schema::dropIfExists('solicitacao_entrega_itens');
    }
}
