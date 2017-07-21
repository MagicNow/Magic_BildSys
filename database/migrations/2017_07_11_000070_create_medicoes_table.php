<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicoesTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicoes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicoes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('mc_medicao_previsao_id');
            $table->decimal('qtd', 19, 2);
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();
            $table->unsignedInteger('user_id');
            $table->tinyInteger('aprovado')->nullable();
            $table->text('obs')->nullable();
            $table->timestamps();


            $table->foreign('mc_medicao_previsao_id')
                ->references('id')->on('mc_medicao_previsoes')
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
       Schema::dropIfExists('medicoes');
     }
}
