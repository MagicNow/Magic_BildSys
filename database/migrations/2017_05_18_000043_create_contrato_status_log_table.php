<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table contrato_status_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_status_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('contrato_status_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamp('created_at');


            $table->foreign('contrato_id')
                ->references('id')->on('contratos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('contrato_status_id')
                ->references('id')->on('contrato_status')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('contrato_status_log');
     }
}
