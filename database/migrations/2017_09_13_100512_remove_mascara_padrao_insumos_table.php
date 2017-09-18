<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMascaraPadraoInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
		Schema::table('mascara_padrao', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('mascara_padrao')->delete();			
            
			$table->dropForeign(['obra_id']);  
			$table->dropForeign(['user_id']);  
			$table->dropColumn(['obra_id']);
			$table->dropColumn(['user_id']);
			
		 });
		
		Schema::table('mascara_padrao_insumos', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('mascara_padrao_insumos')->delete();			
            			
            $table->dropColumn(['terreo_externo_solo']);
			$table->dropColumn(['terreo_externo_estrutura']);
			$table->dropColumn(['terreo_interno']);
			$table->dropColumn(['primeiro_pavimento']);
			$table->dropColumn(['segundo_ao_penultimo']);
			$table->dropColumn(['cobertura_ultimo_piso']);
			$table->dropColumn(['atico']);
			$table->dropColumn(['reservatorio']);
			$table->dropColumn(['qtd_total']);
			$table->dropColumn(['preco_unitario']);
			$table->dropColumn(['preco_total']);
			$table->dropColumn(['referencia_preco']);
			$table->dropColumn(['obs']);
			$table->dropColumn(['porcentagem_orcamento']);
			
			/*$table->unsignedInteger('mascara_padrao_insumos')->nullable();
            $table->foreign('mascara_padrao_insumos')
				->references('id')->on('insumo_grupos')
				->onUpdate('cascade')
				->onDelete('set Null');*/
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
