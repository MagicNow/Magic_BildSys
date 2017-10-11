<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAplicacaoEstoqueInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aplicacao_estoque_insumos', function (Blueprint $table) {
            $table->dropColumn('qtd');
        });
        
        Schema::table('aplicacao_estoque_insumos', function (Blueprint $table) {
            $table->decimal('qtd', 19, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aplicacao_estoque_insumos', function (Blueprint $table) {
            $table->dropColumn('qtd');
        });

        Schema::table('aplicacao_estoque_insumos', function (Blueprint $table) {
            $table->integer('qtd');
        });
    }
}
