<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequisicaoAplicacaoEstoqueLocalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aplicacao_estoque_locais', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pavimento');
            $table->string('andar');
            $table->string('apartamento');
            $table->string('comodo');
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
        Schema::dropIfExists('aplicacao_estoque_locais');
    }
}
