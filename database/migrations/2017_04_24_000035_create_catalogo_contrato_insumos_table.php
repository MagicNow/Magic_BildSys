<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoContratoInsumosTable extends Migration
{
    /**
     * Run the migrations.
     * @table catalogo_contrato_insumos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_contrato_insumos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('catalogo_contrato_id');
            $table->unsignedInteger('insumo_id');
            $table->decimal('valor_unitario', 19, 2)->nullable();
            $table->decimal('valor_maximo', 19, 2)->nullable();
            $table->decimal('pedido_minimo', 19, 2)->nullable();
            $table->decimal('pedido_multiplo_de', 19, 2)->nullable();


            $table->foreign('catalogo_contrato_id')
                ->references('id')->on('catalogo_contratos')
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
         Schema::dropIfExists('catalogo_contrato_insumos');
     }
}
