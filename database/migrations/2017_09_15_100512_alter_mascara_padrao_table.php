<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMascaraPadraoTable extends Migration
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
            
			$table->dropForeign(['orcamento_tipo_id']);  
			$table->dropColumn(['orcamento_tipo_id']);
			
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mascara_padrao_insumos');
	   Schema::dropIfExists('mascara_padrao');
    }
}
