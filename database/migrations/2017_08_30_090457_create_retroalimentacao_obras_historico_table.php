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

            $table->integer('user_id_origem')->unsigned();
            $table->foreign('user_id_origem')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->integer('user_id_destino')->unsigned();
            $table->foreign('user_id_destino')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->string('status_origem', 30);
            $table->string('status_destino', 30);

            $table->text('andamento');

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('retroalimentacao_obras_historico');
        Schema::enableForeignKeyConstraints();
    }
}
