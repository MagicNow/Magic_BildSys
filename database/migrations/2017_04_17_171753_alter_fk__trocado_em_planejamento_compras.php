<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFkTrocadoEmPlanejamentoCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planejamento_compras', function (Blueprint $table){
            $table->dropForeign('planejamento_compras_trocado_de_foreign');
            $table->dropColumn('trocado_de');
            $table->unsignedInteger('insumo_pai')->nullable();
            $table->foreign('insumo_pai')
                ->references('id')
                ->on('insumos')
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
        Schema::table('planejamento_compras', function (Blueprint $table){
            $table->dropForeign(['insumo_pai']);
            $table->unsignedInteger('trocado_de')->nullable();
            $table->foreign('trocado_de')
                ->references('id')
                ->on('planejamento_compras')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
