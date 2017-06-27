<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoContratoStatusLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contrato_status_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('catalogo_contrato_id');
            $table->unsignedInteger('catalogo_contrato_status_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();


            $table->foreign('catalogo_contrato_id', 'fk_catalogo_contrato_status_logs_catalogo_contratos1_idx')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('catalogo_contrato_status_id', 'fk_catalogo_contrato_status_logs_catalogo_contrato_status1_idx')
                ->references('id')->on('catalogo_contrato_status')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_contrato_status_logs');
    }
}
