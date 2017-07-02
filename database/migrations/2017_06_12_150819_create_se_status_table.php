<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('se_status', function($table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('cor');
        });

        Schema::table('solicitacao_entregas', function($table) {
            $table->unsignedInteger('se_status_id');
            $table->foreign('se_status_id')
                ->references('id')
                ->on('se_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitacao_entregas', function($table) {
            $table->dropForeign(['se_status_id']);
            $table->dropColumn('se_status_id');
        });

        Schema::dropIfExists('se_status');
    }
}
