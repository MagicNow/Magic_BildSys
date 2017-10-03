<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoFisicaLogsTable extends Migration
{
    /**
     * Run the migrations.
     * @table catalogo_contratos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_fisica_logs', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('medicao_fisica_id');
			$table->unsignedInteger('user_id');            
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_termino')->nullable();
			$table->decimal('valor_medido_anterior', 19, 2);            
            $table->decimal('valor_medido_atual', 19, 2);            
			$table->timestamps();

            $table->foreign('medicao_fisica_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
			
			$table->foreign('user_id')
                ->references('id')->on('users')
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
