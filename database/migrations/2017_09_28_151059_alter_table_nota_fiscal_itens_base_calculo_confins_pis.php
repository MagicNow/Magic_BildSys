<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscalItensBaseCalculoConfinsPis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nota_fiscal_itens', function (Blueprint $table) {
            $table->decimal('base_calculo_cofins', 19, 2)->nullable();
            $table->decimal('base_calculo_pis', 19, 2)->nullable();
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
            $table->dropColumn('base_calculo_cofins');
            $table->dropColumn('base_calculo_pis');
        });
    }
}
