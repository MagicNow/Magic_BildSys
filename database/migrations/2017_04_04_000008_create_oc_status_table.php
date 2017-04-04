<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOcStatusTable extends Migration
{
    /**
     * Run the migrations.
     * @table oc_status
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oc_status', function (Blueprint $table) {
            
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
       Schema::dropIfExists('oc_status');
     }
}
