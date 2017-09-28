<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcFornecedorAddPagamentoCondicaoIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_fornecedor', function (Blueprint $table) {
            $table->unsignedInteger('pagamento_condicao_id')->nullable();

            $table->foreign('pagamento_condicao_id')
                ->references('id')->on('pagamento_condicoes')
                ->onDelete('set null')
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
        Schema::table('qc_fornecedor', function (Blueprint $table) {
            $table->dropForeign(['pagamento_condicao_id']);
            $table->dropColumn('pagamento_condicao_id');
        });
    }
}
