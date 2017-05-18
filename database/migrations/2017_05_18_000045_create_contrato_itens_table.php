<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoItensTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_itens
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_itens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('insumo_id');
            $table->unsignedInteger('qc_item_id');
            $table->decimal('qtd', 19, 2);
            $table->decimal('valor_unitario', 19, 2);
            $table->decimal('valor_total', 19, 2);
            $table->tinyInteger('aprovado')->default('0');
            $table->timestamps();


            $table->foreign('contrato_id')
                ->references('id')->on('contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('qc_item_id')
                ->references('id')->on('qc_itens')
                ->onDelete('restrict')
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
       Schema::dropIfExists('contrato_itens');
     }
}
