<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMascaraPadraoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mascara_padrao', function (Blueprint $table) {
			
			
            $table->increments('id');
            $table->unsignedInteger('insumo_id');			
			$table->decimal('coeficiente', 19, 2);
			
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
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
        Schema::dropIfExists('mascara_padrao');

    }
}
