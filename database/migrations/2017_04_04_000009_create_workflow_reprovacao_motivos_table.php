<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowReprovacaoMotivosTable extends Migration
{
    /**
     * Run the migrations.
     * @table workflow_reprovacao_motivos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_reprovacao_motivos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('workflow_reprovacao_motivos');
     }
}
