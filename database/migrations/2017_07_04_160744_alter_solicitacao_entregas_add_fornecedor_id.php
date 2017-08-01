<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSolicitacaoEntregasAddFornecedorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacao_entregas', function($table) {
            $table->unsignedInteger('fornecedor_id')->nullable();
            $table->foreign('fornecedor_id')
                ->references('id')
                ->on('fornecedores')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitacao_entregas', function($table) {
            $table->dropForeign(['fornecedor_id']);
            $table->dropColumn('fornecedor_id');
        });
    }
}
