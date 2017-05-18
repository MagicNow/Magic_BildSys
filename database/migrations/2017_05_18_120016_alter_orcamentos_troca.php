<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrcamentosTroca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamentos', function($table) {
            $table->boolean('trocado')->default(0);
            $table->unsignedInteger('orcamento_que_substitui')
                ->nullable()
                ->default(NULL);

            $table->foreign('orcamento_que_substitui')
                ->references('id')
                ->on('orcamentos')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamentos', function() {
            $table->dropColumn('trocado');
            $table->dropColumn('orcamento_que_substitui');
        });
    }
}
