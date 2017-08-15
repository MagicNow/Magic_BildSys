<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscalItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("nota_fiscal_itens", function(Blueprint $table){
            $table->string("nome_produto")->after("codigo_produto");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("nota_fiscal_itens", function(Blueprint $table){
            $table->dropColumn("nome_produto");
        });
    }
}
