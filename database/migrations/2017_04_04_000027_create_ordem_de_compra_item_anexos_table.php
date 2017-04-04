<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdemDeCompraItemAnexosTable extends Migration
{
    /**
     * Run the migrations.
     * @table ordem_de_compra_item_anexos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_de_compra_item_anexos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('ordem_de_compra_item_id');
            $table->string('arquivo');


            $table->foreign('ordem_de_compra_item_id')
                ->references('id')->on('ordem_de_compra_itens')
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
       Schema::dropIfExists('ordem_de_compra_item_anexos');
     }
}
