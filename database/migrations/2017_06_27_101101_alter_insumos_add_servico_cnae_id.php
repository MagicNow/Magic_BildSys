<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInsumosAddServicoCnaeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos', function($table) {
            $table->integer('servico_cnae_id')->nullable()->default(null);

            $table->foreign('servico_cnae_id')
                ->references('id')
                ->on('servicos_cnae')
                ->onDelete('set null')
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
        Schema::table('insumos', function($table) {
            $table->dropForeign(['servico_cnae_id']);
            $table->dropColumn('servico_cnae_id');
        });
    }
}
