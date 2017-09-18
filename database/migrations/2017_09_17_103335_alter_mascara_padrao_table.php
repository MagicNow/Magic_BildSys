<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMascaraPadraoTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamentos
     *
     * @return void
     */
    public function up()
    {
		
		Schema::dropIfExists('mascara_padrao_insumos');		
		Schema::dropIfExists('mascara_padrao');
		
        Schema::create('mascara_padrao', function (Blueprint $table) {
            
            $table->increments('id');
			$table->string('nome', 50);				
			$table->timestamps();
            $table->softDeletes();	
        });
		
        Schema::create('mascara_padrao_insumos', function (Blueprint $table) {
            
            $table->increments('id');			            
            $table->unsignedInteger('mascara_padrao_id');  
			$table->string('codigo', 45);
			$table->unsignedInteger('insumo_id');			          
            $table->decimal('coeficiente', 19, 6)->nullable();
            $table->decimal('indireto', 19, 2)->nullable();            
            
			$table->timestamps();
            $table->softDeletes();			
			
            $table->foreign('mascara_padrao_id')
                ->references('id')->on('mascara_padrao')
                ->onDelete('cascade')
                ->onUpdate('cascade');
				
			$table->foreign('insumo_id')
                ->references('id')->on('insumos')
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
	   Schema::dropIfExists('mascara_padrao_insumos');
	   Schema::dropIfExists('mascara_padrao');
     }
}
