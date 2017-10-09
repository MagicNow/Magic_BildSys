<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscalItensSituacaoIpiConfinsPis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_fiscal_itens', function (Blueprint $table) {
            $table->string("situacao_tributacao_ipi", 10);
            $table->string("situacao_tributacao_cofins", 10);
            $table->string("situacao_tributacao_pis", 10);
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
            $table->dropColumn("situacao_tributacao_ipi");
            $table->dropColumn("situacao_tributacao_cofins");
            $table->dropColumn("situacao_tributacao_pis");
        });
    }
}
