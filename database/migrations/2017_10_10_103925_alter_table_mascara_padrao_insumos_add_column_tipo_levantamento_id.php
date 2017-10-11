<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMascaraPadraoInsumosAddColumnTipoLevantamentoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mascara_padrao_insumos', function(Blueprint $table){
            $table->unsignedInteger('tipo_levantamento_id')->nullable();
            $table->foreign('tipo_levantamento_id')
                ->references('id')->on('tipo_levantamentos')
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
        Schema::table('mascara_padrao_insumos', function(Blueprint $table){
            $table->dropForeign(['tipo_levantamento_id']);
            $table->dropColumn('tipo_levantamento_id');
        });
    }
}
