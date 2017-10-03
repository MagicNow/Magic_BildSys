<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMedicaoFisicaCamposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
		Schema::table('medicao_fisicas', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('medicao_fisicas')->delete();			
            
			$table->dropColumn('periodo_inicio');
			$table->dropColumn('periodo_termino');
			$table->renameColumn('valor_medido', 'valor_medido_total');
			
		 });
        
		Schema::table('medicao_fisica_logs', function (Blueprint $table){

            \Illuminate\Support\Facades\DB::table('medicao_fisica_logs')->delete();			
            
			$table->dropColumn('valor_medido_anterior');
			$table->renameColumn('valor_medido_atual', 'valor_medido');
			
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
