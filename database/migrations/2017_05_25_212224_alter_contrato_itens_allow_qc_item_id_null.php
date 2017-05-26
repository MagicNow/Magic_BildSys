<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoItensAllowQcItemIdNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_itens', function (Blueprint $table) {
            $table->dropForeign(['qc_item_id']);
            $table->unsignedInteger('qc_item_id')->nullable()->change();

            $table->foreign('qc_item_id')
                ->references('id')->on('qc_itens')
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
        Schema::table('contrato_itens', function (Blueprint $table) {
            $table->dropForeign(['qc_item_id']);
            $table->unsignedInteger('qc_item_id')->change();

            $table->foreign('qc_item_id')
                ->references('id')->on('qc_itens')
                ->onDelete('restrict')
                ->onUpdate('cascade');

        });
    }
}
