<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeApropriacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('se_apropriacoes', function($table) {
            $table->increments('id');
            $table->unsignedInteger('solicitacao_entrega_item_id');
            $table->unsignedInteger('contrato_item_apropriacao_id');
            $table->decimal('qtd', 19 ,2);

            $table->foreign('solicitacao_entrega_item_id')
                ->references('id')
                ->on('solicitacao_entrega_itens')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('se_apropriacoes');
    }
}
