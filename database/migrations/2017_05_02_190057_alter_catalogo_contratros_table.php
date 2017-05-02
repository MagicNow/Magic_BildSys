<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoContratrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contratos', function (Blueprint $table){
            $table->unsignedInteger('sugestao_contrato_id')->nullable();

            $table->foreign('sugestao_contrato_id')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->integer('fornecedor_cod');
            $table->string('fornecedor_nome');
        });

        Schema::table('ordem_de_compra_itens', function (Blueprint $table) {
            $table->dropForeign('ordem_de_compra_itens_sugestao_contrato_id_foreign');
            $table->dropColumn('sugestao_contrato_id');
        });

        Schema::table('ordem_de_compra_itens', function (Blueprint $table) {
            $table->unsignedInteger('sugestao_contrato_id')->nullable();

            $table->foreign('sugestao_contrato_id')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::dropIfExists('contrato_insumos');
        Schema::dropIfExists('contratos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_contratos', function (Blueprint $table){
            $table->dropColumn('fornecedor_cod');
            $table->dropColumn('fornecedor_nome');
        });

        Schema::create('contratos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->date('data');
            $table->decimal('valor', 19, 2);
            $table->string('arquivo')->nullable();
            $table->integer('fornecedor_cod');
            $table->string('fornecedor_nome');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('contrato_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('insumo_id');
            $table->decimal('qtd', 19, 2);
            $table->decimal('valor_unitario', 19, 2);
            $table->decimal('valor_total', 19, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('contrato_id')
                ->references('id')->on('contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
