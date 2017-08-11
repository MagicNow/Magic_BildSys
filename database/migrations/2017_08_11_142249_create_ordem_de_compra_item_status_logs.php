<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdemDeCompraItemStatusLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_de_compra_item_logs', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('oc_status_id');
            $table->unsignedInteger('ordem_de_compra_item_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('oc_status_id')
                ->references('id')->on('oc_status')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('ordem_de_compra_item_logs');
    }
}
