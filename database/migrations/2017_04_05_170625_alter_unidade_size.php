<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUnidadeSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidades', function (Blueprint $table){
            $table->string('sigla',10)->change();
        });

        Schema::table('insumos', function (Blueprint $table){
            $table->string('unidade_sigla',10);
            $table->dropForeign(['unidades_sigla']);
            $table->dropColumn(['unidades_sigla']);

            $table->foreign('unidade_sigla')
                ->references('sigla')->on('unidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('ordem_de_compra_itens', function (Blueprint $table){
            $table->string('unidade_sigla',10);
            $table->dropForeign(['unidades_sigla']);
            $table->dropColumn(['unidades_sigla']);

            $table->foreign('unidade_sigla')
                ->references('sigla')->on('unidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('orcamentos', function (Blueprint $table){
            $table->string('unidade_sigla',10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidades', function (Blueprint $table){
            $table->string('sigla',5)->change();
        });

        Schema::table('insumos', function (Blueprint $table){
            $table->string('unidades_sigla',5);
            $table->dropForeign(['unidade_sigla']);
            $table->dropColumn(['unidade_sigla']);

            $table->foreign('unidades_sigla')
                ->references('sigla')->on('unidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('ordem_de_compra_itens', function (Blueprint $table){
            $table->string('unidades_sigla',5)->change();

            $table->dropForeign(['unidade_sigla']);
            $table->dropColumn(['unidade_sigla']);

            $table->foreign('unidades_sigla')
                ->references('sigla')->on('unidades')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('orcamentos', function (Blueprint $table){
            $table->string('unidade_sigla',5)->change();
        });
    }
}
