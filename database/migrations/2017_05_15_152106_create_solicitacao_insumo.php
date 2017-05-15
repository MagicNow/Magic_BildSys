<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitacaoInsumo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacao_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('unidade_sigla', 5);
            $table->string('codigo');
            $table->timestamps();
            $table->softDeletes();


            $table->unsignedInteger('insumo_grupo_id')->nullable();
            $table->foreign('insumo_grupo_id')
                ->references('id')
                ->on('insumo_grupos')
                ->onUpdate('cascade')
                ->onDelete('set Null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacao_insumos');
    }
}
