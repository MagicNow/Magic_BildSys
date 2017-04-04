<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowAprovacoesTable extends Migration
{
    /**
     * Run the migrations.
     * @table workflow_aprovacoes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_aprovacoes', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('workflow_alcada_id');
            $table->integer('aprovavel_id');
            $table->string('aprovavel_type');
            $table->integer('user_id');
            $table->tinyInteger('aprovado');
            $table->string('created_at', 45)->nullable();
            $table->unsignedInteger('workflow_reprovacao_motivo_id')->nullable();
            $table->text('justificativa')->nullable();


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('workflow_alcada_id')
                ->references('id')->on('workflow_alcadas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('workflow_reprovacao_motivo_id')
                ->references('id')->on('workflow_reprovacao_motivos')
                ->onDelete('set null')
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
       Schema::dropIfExists('workflow_aprovacoes');
     }
}
