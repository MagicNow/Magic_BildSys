<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoStatusTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_status
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 45);
            $table->string('cor', 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('contrato_status');
     }
}
