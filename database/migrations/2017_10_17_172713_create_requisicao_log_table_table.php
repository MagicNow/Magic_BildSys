<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisicaoLogTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisicao_log', function (Blueprint $table) {

            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->integer('requisicao_id')->unsigned();
            $table->foreign('requisicao_id')->references('id')->on('requisicao');

            $table->integer('status_id_anterior')->unsigned();
            $table->foreign('status_id_anterior')->references('id')->on('requisicao_status');

            $table->integer('status_id_novo')->unsigned();
            $table->foreign('status_id_novo')->references('id')->on('requisicao_status');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('requisicao_log');
    }
}
