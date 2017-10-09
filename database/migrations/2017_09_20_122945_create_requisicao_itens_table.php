<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisicaoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisicao_itens', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('requisicao_id');

            $table->unsignedInteger('estoque_id');

            $table->string('unidade',10);
            $table->float('qtde', 8, 2)->nullable();

            $table->string('torre',50);
            $table->string('pavimento',50);
            $table->string('trecho',50);
            $table->string('andar',50);
            $table->string('apartamento',50);
            $table->string('comodo',50);

            $table->timestamps();
            $table->softDeletes();


            $table->foreign('requisicao_id')
                ->references('id')->on('requisicao')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('estoque_id')
                ->references('id')->on('estoque')
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
        Schema::dropIfExists('requisicao_itens');
    }
}
