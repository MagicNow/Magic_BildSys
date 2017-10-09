<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscalItensRemoveSolicitacaoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('nota_fiscal_itens', function (Blueprint $table) {
            $table->dropForeign('nota_fiscal_itens_solicitacao_entrega_itens_id_foreign');
            $table->dropColumn('solicitacao_entrega_itens_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nota_fiscal_itens', function (Blueprint $table) {
            $table->unsignedInteger('solicitacao_entrega_itens_id')->nullable();

            $table->foreign('solicitacao_entrega_itens_id')
                ->references('id')->on('solicitacao_entrega_itens')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }
}
