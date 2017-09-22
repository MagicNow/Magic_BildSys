<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMascaraPadraoNewFieldsTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamentos
     *
     * @return void
     */
    public function up()
    {		
		
        Schema::table('mascara_padrao', function (Blueprint $table) {
                        
			$table->string('codigo', 45);					         
            $table->decimal('coeficiente', 19, 6)->nullable();            
			
            $table->unsignedInteger('grupo_id')->nullable();
			$table->unsignedInteger('subgrupo1_id')->nullable();
            $table->unsignedInteger('subgrupo2_id')->nullable();
            $table->unsignedInteger('subgrupo3_id')->nullable();
			$table->unsignedInteger('servico_id')->nullable();			

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
		 Schema::table('mascara_padrao', function (Blueprint $table){
			$table->dropColumn(['codigo']);					         
			$table->dropColumn(['coeficiente']);     

			$table->dropForeign(['grupo_id']);
			$table->dropForeign(['subgrupo1_id']);
			$table->dropForeign(['subgrupo2_id']);
			$table->dropForeign(['subgrupo3_id']);
			$table->dropForeign(['servico_id']);
				
			$table->dropColumn(['grupo_id']);
			$table->dropColumn(['subgrupo1_id']);
			$table->dropColumn(['subgrupo2_id']);
			$table->dropColumn(['subgrupo3_id']);
			$table->dropColumn(['servico_id']);
			 
		 });
		
		
     }
}
