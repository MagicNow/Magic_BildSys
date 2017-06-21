<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoItemModificacaoApropriacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_item_modificacao_apropriacao', function($table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_item_modificacao_id');
            $table->unsignedInteger('contrato_item_apropriacao_id');

            $table->decimal('qtd', 19, 2);

            $table->foreign(
                    'contrato_item_modificacao_id',
                    'cima_contrato_item_modificacao_id_foreign'
                )
                ->references('id')
                ->on('contrato_item_modificacoes')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign(
                    'contrato_item_apropriacao_id',
                    'cima_contrato_item_apropriacao_id_foreign'
                )
                ->references('id')
                ->on('contrato_item_apropriacoes')
                ->onDelete('restrict')
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
        Schema::dropIfExists('contrato_item_modificacao_apropriacao');
    }
}
