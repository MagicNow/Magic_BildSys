<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableContratoItemModificacaoApropriacaoAddColumnDescricao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_modificacao_apropriacao', function (Blueprint $table){
            $table->text('descricao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_item_modificacao_apropriacao', function (Blueprint $table){
            $table->dropColumn('descricao');
        });
    }
}
