<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoContratoRegionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contrato_regional', function (Blueprint $table){
            $table->increments('id');

            $table->integer('catalogo_contrato_id')->unsigned();
            $table->foreign('catalogo_contrato_id')->references('id')->on('catalogo_contratos')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('regional_id')->unsigned();
            $table->foreign('regional_id')->references('id')->on('regionais')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->integer('catalogo_contrato_status_id')->unsigned();
            $table->foreign('catalogo_contrato_status_id','cont_status_id')->references('id')->on('catalogo_contrato_status')->onDelete('restrict')->onUpdate('cascade');

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
        Schema::dropIfExists('catalogo_contrato_regional');
        Schema::enableForeignKeyConstraints();
    }
}
