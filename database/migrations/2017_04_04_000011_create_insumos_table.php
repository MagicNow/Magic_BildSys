<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsumosTable extends Migration
{
    /**
     * Run the migrations.
     * @table insumos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('nome');
            $table->string('unidades_sigla', 5);


            $table->foreign('unidades_sigla')
                ->references('sigla')->on('unidades')
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
       Schema::dropIfExists('insumos');
     }
}
