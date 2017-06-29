<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemoriaCalculoBlocosTable extends Migration
{
    /**
     * Run the migrations.
     * @table memoria_calculo_blocos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memoria_calculo_blocos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('1 = Pré tipo
2 = Tipo
3 = Pós tipos');
            $table->unsignedInteger('memoria_calculo_id');
            $table->unsignedInteger('estrutura');
            $table->unsignedInteger('pavimento');
            $table->unsignedInteger('trecho');


            $table->foreign('estrutura')
                ->references('id')->on('nomeclatura_mapas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('pavimento')
                ->references('id')->on('nomeclatura_mapas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('memoria_calculo_id')
                ->references('id')->on('memoria_calculos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('trecho')
                ->references('id')->on('nomeclatura_mapas')
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
       Schema::dropIfExists('memoria_calculo_blocos');
     }
}
