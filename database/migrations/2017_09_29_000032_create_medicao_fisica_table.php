<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoFisicaTable extends Migration
{
    /**
     * Run the migrations.
     * @table catalogo_contratos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_fisicas', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->string('tarefa', 255)->nullable();
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();
			$table->decimal('valor_medido', 19, 2);            
            $table->timestamps();

            $table->foreign('obra_id')
                ->references('id')->on('obras')
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
       Schema::dropIfExists('medicao_fisicas');
     }
}
