<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesOrdemDeComprasAumentaDecimais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE orcamentos CHANGE preco_unitario preco_unitario NUMERIC(22, 6) DEFAULT NULL;');

        Schema::table('ordem_de_compra_itens', function (Blueprint $table){
            $table->decimal('valor_unitario',22,6)->change();
        });

        DB::statement('ALTER TABLE qc_item_qc_fornecedor CHANGE valor_unitario valor_unitario NUMERIC(22, 6) DEFAULT NULL;');

        Schema::table('contrato_itens', function (Blueprint $table){
            $table->decimal('valor_unitario',22,6)->change();
        });
        Schema::table('contrato_item_modificacoes', function (Blueprint $table){
            $table->decimal('valor_unitario_anterior',22,6)->change();
            $table->decimal('valor_unitario_atual',22,6)->change();
        });
        Schema::table('solicitacao_entrega_itens', function (Blueprint $table){
            $table->decimal('valor_unitario',22,6)->change();
        });

        DB::statement('ALTER TABLE nota_fiscal_itens CHANGE valor_unitario valor_unitario NUMERIC(22, 6) DEFAULT NULL;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE orcamentos CHANGE preco_unitario preco_unitario NUMERIC(19, 2) DEFAULT NULL;');

        Schema::table('ordem_de_compra_itens', function (Blueprint $table){
            $table->decimal('valor_unitario',19,2)->change();
        });
        DB::statement('ALTER TABLE qc_item_qc_fornecedor CHANGE valor_unitario valor_unitario NUMERIC(19, 2) DEFAULT NULL;');

        Schema::table('contrato_itens', function (Blueprint $table){
            $table->decimal('valor_unitario',19,2)->change();
        });
        Schema::table('contrato_item_modificacoes', function (Blueprint $table){
            $table->decimal('valor_unitario_anterior',19,2)->change();
            $table->decimal('valor_unitario_atual',19,2)->change();
        });
        Schema::table('solicitacao_entrega_itens', function (Blueprint $table){
            $table->decimal('valor_unitario',19,2)->change();
        });

        DB::statement('ALTER TABLE nota_fiscal_itens CHANGE valor_unitario valor_unitario NUMERIC(19, 2) DEFAULT NULL;');
    }
}
