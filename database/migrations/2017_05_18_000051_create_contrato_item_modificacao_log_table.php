<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoItemModificacaoLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_item_modificacao_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_item_modificacao_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('created_at');
            $table->unsignedInteger('contrato_item_modificacao_id');
            $table->unsignedInteger('contrato_status_id');


            $table->foreign('contrato_item_modificacao_id', 'fk_log_item_modificao_id')
                ->references('id')->on('contrato_item_modificacoes')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('contrato_status_id', 'fk_log_contrato_status_id')
                ->references('id')->on('contrato_status')
                ->onDelete('restrict')
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
       Schema::dropIfExists('contrato_item_modificacao_log');
     }
}
