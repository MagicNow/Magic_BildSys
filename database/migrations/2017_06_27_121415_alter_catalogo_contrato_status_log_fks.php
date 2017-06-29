<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCatalogoContratoStatusLogFks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogo_contrato_status_logs', function (Blueprint $table) {
            $table->dropForeign('fk_catalogo_contrato_status_logs_catalogo_contratos1_idx');
            $table->dropForeign('fk_catalogo_contrato_status_logs_catalogo_contrato_status1_idx');


            $table->foreign('catalogo_contrato_id', 'fk_catalogo_contrato_status_logs_catalogo_contratos1_idx')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('catalogo_contrato_status_id', 'fk_catalogo_contrato_status_logs_catalogo_contrato_status1_idx')
                ->references('id')->on('catalogo_contrato_status')
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
        
    }
}
