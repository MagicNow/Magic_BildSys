<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObraTorresTable extends Migration
{
    /**
     * Run the migrations.
     * @table obra_torres
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obra_torres', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->string('nome', 45);


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
       Schema::dropIfExists('obra_torres');
     }
}
