<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcStatusTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_status
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255);
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
       Schema::dropIfExists('qc_status');
     }
}
