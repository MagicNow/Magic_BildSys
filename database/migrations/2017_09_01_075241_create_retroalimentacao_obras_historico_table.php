<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetroalimentacaoObrasHistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retroalimentacao_obras_historico', function (Blueprint $table){

            $table->increments('id');

            $table->integer('retroalimentacao_obras_id')->unsigned();
            $table->foreign('retroalimentacao_obras_id','retro_obras_id')->references('id')->on('retroalimentacao_obras')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id_origem')->unsigned()->nullable();
            $table->foreign('user_id_origem')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->integer('user_id_destino')->unsigned()->nullable();
            $table->foreign('user_id_destino')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->integer('status_origem')->unsigned()->nullable();
            $table->foreign('status_origem')->references('id')->on('retroalimentacao_obras_status')->onDelete('restrict')->onUpdate('cascade');

            $table->integer('status_destino')->unsigned()->nullable();
            $table->foreign('status_destino')->references('id')->on('retroalimentacao_obras_status')->onDelete('restrict')->onUpdate('cascade');

            $table->text('andamento');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('retroalimentacao_obras_historico');
        Schema::enableForeignKeyConstraints();
    }
}
