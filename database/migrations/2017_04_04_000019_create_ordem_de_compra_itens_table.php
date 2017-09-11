<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdemDeCompraItensTable extends Migration
{
    /**
     * Run the migrations.
     * @table ordem_de_compra_itens
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ordem_de_compra_itens');
        Schema::create('ordem_de_compra_itens', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('ordem_de_compra_id');
            $table->unsignedInteger('obra_id');
            $table->string('codigo_insumo');
            $table->decimal('qtd', 19, 2);
            $table->decimal('valor_unitario', 19, 2);
            $table->decimal('valor_total', 19, 2);
            $table->tinyInteger('aprovado')->nullable();
            $table->text('obs')->nullable();
            $table->text('justificativa')->nullable();
            $table->text('tems')->nullable();
            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('servico_id');
            $table->unsignedInteger('insumo_id');
            $table->string('unidades_sigla', 5);
            $table->tinyInteger('emergencial')->default('0');
            $table->date('sugestao_data_uso')->nullable();
            $table->unsignedInteger('sugestao_contrato_id')->nullable();
            $table->unsignedInteger('user_id');

            $table->softDeletes();
            $table->timestamps();


            $table->foreign('ordem_de_compra_id')
                ->references('id')->on('ordem_de_compras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
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

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('unidades_sigla')
                ->references('sigla')->on('unidades')
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
       Schema::dropIfExists('ordem_de_compra_itens');
     }
}
