<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItemReapropriacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_reapropriacoes', function($table) {
            $table->dropForeign(['ordem_de_compra_item_id']);
            $table->dropColumn('ordem_de_compra_item_id');
            $table->boolean('reapropriacao');
        });

        Schema::rename('contrato_item_reapropriacoes', 'contrato_item_apropriacoes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_item_apropriacoes', function($table) {
            $table->dropColumn('reapropriacao');

            $table->unsignedInteger('ordem_de_compra_item_id')->nullable();

            $table->foreign('ordem_de_compra_item_id')
                ->references('id')
                ->on('ordem_de_compra_itens')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::rename('contrato_item_apropriacoes', 'contrato_item_reapropriacoes');
    }
}
