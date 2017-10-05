<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisicaoSaidaLeituraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisicao_saida_leitura', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qtd_lida', 19, 6);
            $table->unsignedInteger('requisicao_item_id');

            $table->foreign('requisicao_item_id')
                ->references('id')->on('requisicao_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisicao_saida_leitura');
    }
}
