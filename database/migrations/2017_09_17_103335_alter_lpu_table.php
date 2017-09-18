<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLpuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
		Schema::dropIfExists('lpu');
			
        Schema::create('lpu', function (Blueprint $table) {			
			
            $table->increments('id');
            $table->unsignedInteger('insumo_id');
			$table->string('codigo_insumo',100);
			$table->unsignedInteger('regional_id');            			
			$table->decimal('valor_sugerido', 19, 2);
			$table->decimal('valor_contrato', 19, 2);
			$table->decimal('valor_catalogo', 19, 2);
			$table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('regional_id')
                ->references('id')->on('regionais')
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
        Schema::dropIfExists('lpu');

    }
}
