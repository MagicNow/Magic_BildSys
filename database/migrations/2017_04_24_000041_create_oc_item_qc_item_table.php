<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOcItemQcItemTable extends Migration
{
    /**
     * Run the migrations.
     * @table oc_item_qc_item
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oc_item_qc_item', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('ordem_de_compra_item_id');
            $table->unsignedInteger('qc_item_id');
            $table->timestamps();


            $table->foreign('ordem_de_compra_item_id')
                ->references('id')->on('ordem_de_compra_itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('qc_item_id')
                ->references('id')->on('qc_itens')
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
       Schema::dropIfExists('oc_item_qc_item');
     }
}
