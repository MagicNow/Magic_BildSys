<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicosTable extends Migration
{
    /**
     * Run the migrations.
     * @table servicos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('codigo', 45);
            $table->string('nome');
            $table->unsignedInteger('grupo_id');
            $table->softDeletes();
            $table->nullableTimestamps();


            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
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
       Schema::dropIfExists('servicos');
     }
}
