<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronogramaFisicosTable extends Migration
{
    /**
     * Run the migrations.
     * @table carteiras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cronograma_fisicos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('nome');
			$table->boolean('active')->default(1);
			$table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('carteiras');
     }
}
