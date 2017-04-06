<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObrasTable extends Migration
{
    /**
     * Run the migrations.
     * @table obras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obras', function (Blueprint $table) {
            
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
       Schema::dropIfExists('obras');
     }
}
