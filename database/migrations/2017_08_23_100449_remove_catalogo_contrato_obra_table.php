<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCatalogoContratoObraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('catalogo_contrato_obra');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
    }
}
