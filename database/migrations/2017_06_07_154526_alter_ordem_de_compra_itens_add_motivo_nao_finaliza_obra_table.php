<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdemDeCompraItensAddMotivoNaoFinalizaObraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordem_de_compra_itens', function (Blueprint $table) {
           $table->text('motivo_nao_finaliza_obra')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordem_de_compra_itens', function (Blueprint $table) {
            $table->dropColumn('motivo_nao_finaliza_obra');
        });
    }
}
