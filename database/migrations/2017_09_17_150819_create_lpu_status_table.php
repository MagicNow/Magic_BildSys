<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLpuStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lpu_status', function($table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('cor');
			$table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lpu', function($table) {
            $table->dropForeign(['lpu_status_id']);
            $table->dropColumn('lpu_status_id');
        });

        Schema::dropIfExists('lpu_status');
    }
}
