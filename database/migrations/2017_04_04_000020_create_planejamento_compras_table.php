<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanejamentoComprasTable extends Migration
{
    /**
     * Run the migrations.
     * @table planejamento_compras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planejamento_compras', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('planejamento_id');
            $table->unsignedInteger('grupo_id')->nullable();
            $table->unsignedInteger('servico_id')->nullable();
            $table->string('codigo_insumo', 45)->nullable();


            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
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
       Schema::dropIfExists('planejamento_compras');
     }
}
