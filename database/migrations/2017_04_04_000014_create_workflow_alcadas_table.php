<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowAlcadasTable extends Migration
{
    /**
     * Run the migrations.
     * @table workflow_alcadas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_alcadas', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('workflow_tipo_id');
            $table->string('nome');
            $table->integer('ordem')->nullable()->default('1');


            $table->foreign('workflow_tipo_id')
                ->references('id')->on('workflow_tipos')
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
       Schema::dropIfExists('workflow_alcadas');
     }
}
