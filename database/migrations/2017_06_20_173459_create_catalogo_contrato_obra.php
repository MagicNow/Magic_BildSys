<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoContratoObra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contrato_obra', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('catalogo_contrato_id');
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('catalogo_contrato_status_id');

            $table->foreign('catalogo_contrato_id')
                ->references('id')->on('catalogo_contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('catalogo_contrato_status_id')
                ->references('id')->on('catalogo_contrato_status')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });
        Schema::create('catalogo_contrato_obra_logs', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('catalogo_contrato_obra_id');
            $table->unsignedInteger('catalogo_contrato_status_id');

            $table->foreign('catalogo_contrato_obra_id')
                ->references('id')->on('catalogo_contrato_obra')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('catalogo_contrato_status_id')
                ->references('id')->on('catalogo_contrato_status')
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
        Schema::dropIfExists('catalogo_contrato_obra_logs');
        Schema::dropIfExists('catalogo_contrato_obra');
    }
}
