<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdemDeCompraItensSugestaoContratoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('ordem_de_compra_itens', function($table) {
            $table->dropForeign(['sugestao_contrato_id']);
            $table->foreign('sugestao_contrato_id')
                ->references('id')
                ->on('contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('ordem_de_compra_itens', function($table) {
            $table->dropForeign(['sugestao_contrato_id']);
            $table->foreign('sugestao_contrato_id')
                ->references('id')
                ->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }
}
