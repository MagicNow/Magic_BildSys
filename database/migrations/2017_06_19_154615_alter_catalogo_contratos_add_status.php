<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoContratosAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contratos', function (Blueprint $table){
            $table->string('minuta_assinada')->nullable();
            $table->unsignedInteger('catalogo_contrato_status_id')->nullable();
            $table->foreign('catalogo_contrato_status_id')
                ->references('id')->on('catalogo_contrato_status')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->text('campos_extras_contrato')->nullable();
            $table->text('campos_extras_minuta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogo_contratos', function (Blueprint $table){
            $table->dropForeign(['catalogo_contrato_status_id']);
            $table->dropColumn('catalogo_contrato_status_id');
            $table->dropColumn('minuta_assinada');
            $table->dropColumn('campos_extras_minuta');
            $table->dropColumn('campos_extras_contrato');
        });
    }
}
