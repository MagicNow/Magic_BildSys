<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarefaPadraoTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamentos
     *
     * @return void
     */
    public function up()
    {
		Schema::dropIfExists('tarefa_padrao');
        Schema::create('tarefa_padrao', function (Blueprint $table) {
            
            $table->increments('id');
			$table->string('tarefa', 255);			
			$table->string('resumo')->nullable();
			$table->string('torre')->nullable();
			$table->string('pavimento')->nullable();
			$table->string('critica')->nullable();
				
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
       Schema::dropIfExists('tarefa_padrao');
     }
}
