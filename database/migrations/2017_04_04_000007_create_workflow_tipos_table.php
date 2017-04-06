<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowTiposTable extends Migration
{
    /**
     * Run the migrations.
     * @table workflow_tipos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_tipos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('nome', 245);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('workflow_tipos');
     }
}
