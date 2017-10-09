<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEstoqueTransacaoAddForeignNfSeItemIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_transacao', function (Blueprint $table) {
            $table->dropColumn('nf_se_item_id');
        });

        Schema::table('estoque_transacao', function (Blueprint $table) {
            $table->unsignedInteger('nf_se_item_id');
            $table->foreign('nf_se_item_id')
                ->references('id')->on('nf_se_item')
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
        Schema::table('estoque_transacao', function (Blueprint $table) {
            $table->dropForeign(['nf_se_item_id']);
        });
    }
}
