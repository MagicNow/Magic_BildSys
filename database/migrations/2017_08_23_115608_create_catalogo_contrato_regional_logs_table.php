<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoContratoRegionalLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contrato_regional_logs', function (Blueprint $table){
            $table->increments('id');

            $table->integer('catalogo_contrato_regional_id')->unsigned();
            $table->foreign('catalogo_contrato_regional_id','cc_regional_id')
                ->references('id')
                ->on('catalogo_contrato_regional')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('catalogo_contrato_status_id')->unsigned();
            $table->foreign('catalogo_contrato_status_id','cc_status_id')
                ->references('id')
                ->on('catalogo_contrato_status')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('catalogo_contrato_regional_logs');
        Schema::enableForeignKeyConstraints();
    }
}
