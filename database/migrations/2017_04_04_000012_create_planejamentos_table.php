<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanejamentosTable extends Migration
{
    /**
     * Run the migrations.
     * @table planejamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planejamentos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->string('tarefa');
            $table->date('data');
            $table->integer('prazo');
            $table->string('created_at', 45)->nullable();
            $table->unsignedInteger('planejamento_id');


            $table->foreign('planejamento_id')
                ->references('id')->on('planejamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
       Schema::dropIfExists('planejamentos');
     }
}
