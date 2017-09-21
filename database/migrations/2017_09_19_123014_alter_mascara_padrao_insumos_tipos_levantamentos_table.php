<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMascaraPadraoInsumosTiposLevantamentosTable extends Migration

{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mascara_padrao_insumos', function (Blueprint $table){
			$table->unsignedInteger('tipo_levantamento_id');
			$table->foreign('tipo_levantamento_id')
                ->references('id')
                ->on('tipo_levantamentos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mascara_padrao_insumos', function (Blueprint $table){
            $table->dropColumn('tipo_levantamento_id');
			$table->dropForeign(['tipo_levantamento_id']);
        });
    }
}
