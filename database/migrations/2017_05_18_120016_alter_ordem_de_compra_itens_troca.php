<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdemDeCompraItensTroca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordem_de_compra_itens', function($table) {
            $table->boolean('trocado')->default(0);
            $table->unsignedInteger('item_que_substitui')->nullable()->default(NULL);

            $table->foreign('item_que_substitui')
                ->references('id')
                ->on('ordem_de_compra_itens')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordem_de_compra_itens', function() {
            $table->dropColumn('trocado');
            $table->dropColumn('item_que_substitui');
        });
    }
}
