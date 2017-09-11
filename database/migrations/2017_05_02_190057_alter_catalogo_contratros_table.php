<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoContratrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contratos', function (Blueprint $table){
            $table->unsignedInteger('sugestao_contrato_id')->nullable();

            $table->foreign('sugestao_contrato_id')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('ordem_de_compra_itens', function (Blueprint $table) {
//            $table->dropForeign('ordem_de_compra_itens_sugestao_contrato_id_foreign');
            $table->dropColumn('sugestao_contrato_id');
        });

        Schema::table('ordem_de_compra_itens', function (Blueprint $table) {
            $table->unsignedInteger('sugestao_contrato_id')->nullable();

            $table->foreign('sugestao_contrato_id')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::dropIfExists('contrato_insumos');
        Schema::dropIfExists('contratos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
