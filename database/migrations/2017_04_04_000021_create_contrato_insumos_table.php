<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoInsumosTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_insumos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_insumos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('insumo_id');
            $table->decimal('qtd', 19, 2);
            $table->decimal('valor_unitario', 19, 2);
            $table->decimal('valor_total', 19, 2);


            $table->foreign('contrato_id')
                ->references('id')->on('contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
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
       Schema::dropIfExists('contrato_insumos');
     }
}
