<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrcamentoTiposTable extends Migration
{
    /**
     * Run the migrations.
     * @table orcamento_tipos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamento_tipos', function (Blueprint $table) {
            
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
       Schema::dropIfExists('orcamento_tipos');
     }
}
