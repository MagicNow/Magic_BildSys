<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscalItensCodigoEnquadramentoIpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_fiscal_itens', function (Blueprint $table) {
            $table->string("codigo_enquadramento_ipi", 10)->nullable();
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
            $table->dropColumn('codigo_enquadramento_ipi');
        });
    }
}
