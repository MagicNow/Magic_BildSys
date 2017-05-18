<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoItemReapropriacoesTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_item_reapropriacoes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_item_reapropriacoes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_item_id');
            $table->unsignedInteger('ordem_de_compra_item_id');
            $table->string('codigo_insumo', 255)->nullable();
            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('servico_id');
            $table->unsignedInteger('insumo_id');
            $table->decimal('qtd', 19, 2)->nullable();
            $table->unsignedInteger('user_id')->nullable();


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('contrato_item_id')
                ->references('id')->on('contrato_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('ordem_de_compra_item_id')
                ->references('id')->on('ordem_de_compra_itens')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
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
       Schema::dropIfExists('contrato_item_reapropriacoes');
     }
}
