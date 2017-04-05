<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLembreteTiposTable extends Migration
{
    /**
     * Run the migrations.
     * @table lembrete_tipos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembrete_tipos', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('nome');
            $table->integer('dias_prazo_minimo');
            $table->integer('dias_prazo_maximo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('lembrete_tipos');
     }
}
