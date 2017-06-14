<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacaoLigacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacao_ligacoes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('contrato_item_apropriacao_id');
            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('servico_id');
            $table->unsignedInteger('insumo_id');

            $table->timestamps();

            $table->foreign('contrato_item_apropriacao_id')
                ->references('id')
                ->on('contrato_item_apropriacoes')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')
                ->references('id')
                ->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')
                ->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')
                ->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')
                ->on('grupos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')
                ->on('servicos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')
                ->on('insumos')
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
        Schema::dropIfExists('apropriacao_ligacoes');
    }
}
