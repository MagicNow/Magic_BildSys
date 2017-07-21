<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMedicaoServicosAddContratoItemApropriacaoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicao_servicos', function (Blueprint $table){
            $table->unsignedInteger('contrato_item_apropriacao_id');
            $table->foreign('contrato_item_apropriacao_id')
                ->references('id')
                ->on('contrato_item_apropriacoes')
                ->onDelete('cascade')
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
        Schema::table('medicao_servicos', function (Blueprint $table){
            $table->dropForeign(['contrato_item_apropriacao_id']);
            $table->dropColumn('contrato_item_apropriacao_id');
        });
    }
}
