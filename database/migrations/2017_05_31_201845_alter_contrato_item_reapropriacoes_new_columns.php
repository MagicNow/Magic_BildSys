<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItemReapropriacoesNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_item_reapropriacoes', function($table) {
            $table->unsignedInteger('contrato_item_reapropriacao_id')->nullable();
            $table->unsignedInteger('ordem_de_compra_item_id')->nullable()->change();

            $table->foreign('contrato_item_reapropriacao_id', 'contrato_item_reapropriacao_id_foreign')
                ->references('id')
                ->on('contrato_item_reapropriacoes')
                ->onDelete('set null')
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
        Schema::table('contrato_item_reapropriacoes', function($table) {
            $table->unsignedInteger('ordem_de_compra_item_id')->change();
            $table->dropColumn('contrato_item_reapropriacao_id');
        });
    }
}
