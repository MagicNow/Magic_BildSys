<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     * @table contratos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->date('data');
            $table->decimal('valor', 19, 2);
            $table->string('arquivo')->nullable();


            $table->foreign('obra_id')
                ->references('id')->on('obras')
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
       Schema::dropIfExists('contratos');
     }
}
