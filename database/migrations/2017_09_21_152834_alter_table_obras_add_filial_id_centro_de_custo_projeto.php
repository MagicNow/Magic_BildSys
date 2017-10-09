<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableObrasAddFilialIdCentroDeCustoProjeto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obras', function(Blueprint $table) {
            $table->integer('codigo_centro_de_custo')->nullable();
            $table->integer('codigo_projeto_padrao')->nullable();
            $table->integer('filial_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obras', function(Blueprint $table) {
            $table->dropColumn('codigo_centro_de_custo');
            $table->dropColumn('codigo_projeto_padrao');
            $table->dropColumn('filial_id');
        });
    }
}
