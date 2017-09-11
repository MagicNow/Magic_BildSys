<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteracaoMudancasEstruturais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumo_grupos', function (Blueprint $table){
            $table->unsignedInteger('id');
            $table->primary('id');
            $table->string('codigo_identificador');
            $table->string('nome');
        });

        Schema::table('insumos', function (Blueprint $table){
            $table->unsignedInteger('insumo_grupo_id')->nullable();
            $table->foreign('insumo_grupo_id')->references('id')->on('insumo_grupos')->onUpdate('cascade')->onDelete('set Null');
        });

        Schema::table('planejamento_compras', function (Blueprint $table){
            $table->unsignedInteger('insumo_id')->nullable();
            $table->foreign('insumo_id')->references('id')->on('insumos')->onUpdate('cascade')->onDelete('set Null');
            $table->dropForeign(['grupo_id']);
            $table->dropForeign(['servico_id']);
            $table->dropColumn(
                'grupo_id',
                'servico_id',
                'codigo_insumo'
            );
        });

        Schema::table('lembretes', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('lembretes')->delete();
            $table->dropForeign(['planejamento_id']);
            $table->dropColumn(['planejamento_id']);

            $table->unsignedInteger('insumo_grupo_id');

            $table->foreign('insumo_grupo_id')
                ->references('id')->on('insumo_grupos')
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
        Schema::table('lembretes', function (Blueprint $table) {


            $table->unsignedInteger('planejamento_id');
            
            $table->dropForeign(['insumo_grupo_id']);
            
            $table->dropColumn('insumo_grupo_id');

            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::table('insumos', function (Blueprint $table){
            $table->dropForeign(['insumo_grupo_id']);
            $table->dropColumn('insumo_grupo_id');
        });

        Schema::table('planejamento_compras', function (Blueprint $table) {

            $table->dropForeign(['insumo_id']);
            $table->dropColumn(['insumo_id']);

            $table->unsignedInteger('grupo_id')->nullable();
            $table->unsignedInteger('servico_id')->nullable();
            $table->string('codigo_insumo', 45)->nullable();


            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::drop('insumo_grupos');
    }
}
