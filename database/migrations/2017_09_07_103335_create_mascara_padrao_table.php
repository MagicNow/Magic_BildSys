<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMascaraPadraoTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mascara_padrao', function (Blueprint $table) {
            
            $table->increments('id');
			$table->string('nome', 50);
			$table->unsignedInteger('obra_id');					
			$table->unsignedInteger('orcamento_tipo_id');           
            $table->unsignedInteger('user_id');
			
			$table->foreign('obra_id')
				->references('id')->on('obras')
				->onUpdate('cascade')
				->onDelete('cascade');
			
            $table->foreign('orcamento_tipo_id')
                ->references('id')->on('orcamento_tipos')
                ->onDelete('restrict')
                ->onUpdate('cascade');			
			
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

				
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
       Schema::dropIfExists('mascara_padrao');
     }
}
