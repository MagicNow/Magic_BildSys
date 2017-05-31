<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrcamentoAddColumnInsumoAdicionado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->integer('insumo_incluido')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn('insumo_incluido');
        });
    }
}
