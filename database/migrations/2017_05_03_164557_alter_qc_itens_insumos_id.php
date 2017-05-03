<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcItensInsumosId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_itens', function(Blueprint $table){
            $table->dropForeign(['insumos_id']);
            $table->dropColumn(['insumos_id']);

            $table->unsignedInteger('insumo_id');
            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
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
        Schema::table('qc_itens', function(Blueprint $table){
            $table->dropForeign(['insumo_id']);
            $table->dropColumn(['insumo_id']);

            $table->unsignedInteger('insumos_id');
            $table->foreign('insumos_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }
}
