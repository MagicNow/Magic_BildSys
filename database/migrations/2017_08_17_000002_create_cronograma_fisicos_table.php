<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronogramaFisicosTable extends Migration
{
    /**
     * Run the migrations.
     * @table carteiras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronograma_fisicos', function (Blueprint $table) {
            
			$table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->string('tarefa');	
			$table->string('tipo');
			$table->string('custo');
			$table->string('resumo');
			$table->string('torre');
			$table->string('pavimento');
			$table->string('critica');
			$table->string('concluida');					
            $table->date('data_inicio');
			$table->date('data_termino');                  

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
			
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
       Schema::dropIfExists('cronograma_fisicos');
     }
}
