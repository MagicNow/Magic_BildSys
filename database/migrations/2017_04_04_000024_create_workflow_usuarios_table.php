<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     * @table workflow_usuarios
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_usuarios', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('workflow_alcada_id');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('workflow_alcada_id')
                ->references('id')->on('workflow_alcadas')
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
       Schema::dropIfExists('workflow_usuarios');
     }
}
