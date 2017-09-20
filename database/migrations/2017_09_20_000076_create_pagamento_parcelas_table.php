<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNfSeItemTable extends Migration
{
    /**
     * Run the migrations.
     * @table nf_se_item
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nf_se_item', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('nota_fiscal_item_id');
            $table->unsignedInteger('solicitacao_entrega_item_id');
            $table->nullableTimestamps();


            $table->foreign('nota_fiscal_item_id', 'fk_nota_fiscal_itens_has_solicitacao_entrega_itens_nota_fis_idx')
                ->references('id')->on('nota_fiscal_itens')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('solicitacao_entrega_item_id', 'fk_nota_fiscal_itens_has_solicitacao_entrega_itens_solicita_idx')
                ->references('id')->on('solicitacao_entrega_itens')
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
       Schema::dropIfExists('nf_se_item');
     }
}
