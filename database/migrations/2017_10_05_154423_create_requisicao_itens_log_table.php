<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisicaoItensLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('requisicao_itens_log', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->integer('requisicao_itens_id')->unsigned();
            $table->foreign('requisicao_itens_id')->references('id')->on('requisicao_itens');

            $table->float('qtde_anterior', 8, 2)->nullable();
            $table->float('qtde_nova', 8, 2)->nullable();

            $table->integer('status_id_anterior')->unsigned()->nullable();
            $table->integer('status_id_novo')->unsigned()->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('status_id_novo')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisicao_itens_log');
    }
}
