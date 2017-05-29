<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItemModificacoesColumnQtdAnterior extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_modificacoes', function (Blueprint $table) {
            $table->decimal('qtd_anterior', 19, 2);
            $table->dropColumn('qtd_aterior');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_item_modificacoes', function (Blueprint $table) {
            $table->decimal('qtd_aterior', 19, 2);
            $table->dropColumn('qtd_anterior');
        });
    }
}
