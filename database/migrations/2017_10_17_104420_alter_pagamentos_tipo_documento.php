<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPagamentosTipoDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagamentos', function (Blueprint $table){
            $table->dropForeign(['documento_tipo_id']);
            $table->dropColumn('documento_tipo_id');
            $table->unsignedInteger('documento_financeiro_tipo_id')->nullable();
            $table->foreign('documento_financeiro_tipo_id')->references('id')->on('documento_financeiro_tipos')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagamentos', function (Blueprint $table){
            $table->dropForeign(['documento_financeiro_tipo_id']);
            $table->dropColumn('documento_financeiro_tipo_id');
            $table->unsignedInteger('documento_tipo_id')->nullable();
            $table->foreign('documento_tipo_id')->references('id')->on('documento_tipos')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }
}
