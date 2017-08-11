<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarteiraTipoEqualizacaoTecnicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carteira_tipo_equalizacao_tecnicas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tipo_equalizacao_id');
            $table->unsignedInteger('carteira_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tipo_equalizacao_id')
                ->references('id')->on('tipo_equalizacao_tecnicas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('carteira_id')
                ->references('id')->on('carteiras')
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
        Schema::dropIfExists('carteira_tipo_equalizacao_tecnicas');

    }
}
