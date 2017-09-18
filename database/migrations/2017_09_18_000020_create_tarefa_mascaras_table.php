<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTarefaMascarasTable extends Migration
{
    /**
     * Run the migrations.
     * @table planejamento_compras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarefa_mascaras', function (Blueprint $table) {
            
            $table->increments('id');
			$table->unsignedInteger('obra_id');
			$table->unsignedInteger('mascara_padrao_id');
            $table->unsignedInteger('tarefa_padrao_id'); 
			$table->unsignedInteger('insumo_id');			
            $table->string('codigo_estruturado', 45)->nullable();
            
            $table->unsignedInteger('grupo_id');
			$table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');            
			$table->unsignedInteger('servico_id');
			
			$table->timestamps();
            $table->softDeletes();

			$table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
			
            $table->foreign('mascara_padrao_id')
                ->references('id')->on('mascara_padrao')
                ->onDelete('cascade')
                ->onUpdate('cascade');
				
			$table->foreign('tarefa_padrao_id')
                ->references('id')->on('tarefa_padrao')
                ->onDelete('cascade')
                ->onUpdate('cascade');
				
			$table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');		
			
			$table->foreign('servico_id')
                ->references('id')->on('servicos')
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
       Schema::dropIfExists('tarefa_mascaras');
     }
}
