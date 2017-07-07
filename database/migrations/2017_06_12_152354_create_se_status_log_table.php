<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('se_status_log', function($table) {
            $table->increments('id');
            $table->unsignedInteger('solicitacao_entrega_id');
            $table->unsignedInteger('se_status_id');
            $table->unsignedInteger('user_id');
            $table->dateTime('created_at');

            $table->foreign('se_status_id')
                ->references('id')
                ->on('se_status');

            $table->foreign('solicitacao_entrega_id')
                ->references('id')
                ->on('solicitacao_entregas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('se_status_log');
    }
}
