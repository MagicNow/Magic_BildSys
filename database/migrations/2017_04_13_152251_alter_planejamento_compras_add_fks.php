<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlanejamentoComprasAddFks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table('planejamento_compras')->delete();
        Schema::table('planejamento_compras', function (Blueprint $table){
            $table->string('codigo_estruturado')->nullable();
            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('servico_id');
            $table->unsignedInteger('trocado_de')->nullable();

            $table->foreign('grupo_id')
                ->references('id')
                ->on('grupos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')
                ->on('grupos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')
                ->on('grupos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')
                ->on('grupos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('servico_id')
                ->references('id')
                ->on('servicos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('trocado_de')
                ->references('id')
                ->on('planejamento_compras')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('planejamento_compras')->delete();
        Schema::table('planejamento_compras', function (Blueprint $table){
            $table->dropForeign([
                'grupo_id'
                ]);
            $table->dropForeign([
                'subgrupo1_id'
                ]);
            $table->dropForeign([
                'subgrupo2_id'
                ]);
            $table->dropForeign([
                'subgrupo3_id'
                ]);
            $table->dropForeign([
                'servico_id'
                ]);
            $table->dropForeign([
                'trocado_de'
                ]);

            $table->dropColumn([
                'grupo_id',
                'subgrupo1_id',
                'subgrupo2_id',
                'subgrupo3_id',
                'servico_id',
                'trocado_de',
                'codigo_estruturado'
            ]);
        });
    }
}
