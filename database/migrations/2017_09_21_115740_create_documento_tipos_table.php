<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoTiposTable extends Migration
{
    /**
     * Run the migrations.
     * @table documento_tipos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_tipos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255);
            $table->string('sigla', 20);
            $table->integer('codigo_mega');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documento_tipos');
    }
}


