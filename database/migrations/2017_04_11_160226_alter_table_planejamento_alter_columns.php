<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePlanejamentoAlterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planejamentos', function (Blueprint $table) {
            $table->date('data_fim')->nullable();
            $table->string('resumo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planejamentos', function (Blueprint $table) {
            $table->dropColumn('data_fim');
            $table->dropColumn('resumo');
        });
    }
}
