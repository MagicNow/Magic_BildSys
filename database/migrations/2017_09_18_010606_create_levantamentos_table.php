<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLevantamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::dropIfExists('levantamentos');
		Schema::create('levantamentos', function (Blueprint $table) {			
			
            $table->increments('id');
            $table->unsignedInteger('obra_id');
			$table->string('apropriacao', 45)->nullable();
			$table->string('insumo',100);
			$table->string('torre',100);
			$table->string('andar',100);
			$table->unsignedInteger('pavimento',100);
			$table->unsignedInteger('trecho',100);
			$table->string('apartamento',100);
			$table->string('comodo',100);
			$table->string('parede',100);
			$table->string('trecho_parede',100);
			$table->string('personalizavel',100);
			$table->unsignedInteger('quantidade')->nullable();			
			$table->string('perda',100);	
			$table->string('planta',100);
			$table->string('planta_opcao',100);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('obra_id')
                ->references('id')->on('obras')
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
        Schema::dropIfExists('levantamentos');
    }
}
