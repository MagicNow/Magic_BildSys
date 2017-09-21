<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMascaraPadraoUserTable extends Migration
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
            
			$table->dropForeign(['user_id']);  
			$table->dropColumn(['user_id']);
			
		 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	   Schema::dropIfExists('mascara_padrao');
    }
}
