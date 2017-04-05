<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsumoServicoTable extends Migration
{
    /**
     * Run the migrations.
     * @table insumo_servico
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumo_servico', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('servicos_id');
            $table->unsignedInteger('insumos_id');


            $table->foreign('servicos_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumos_id')
                ->references('id')->on('insumos')
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
       Schema::dropIfExists('insumo_servico');
     }
}
