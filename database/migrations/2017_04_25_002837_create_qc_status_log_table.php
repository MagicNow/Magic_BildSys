<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_status_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('quadro_de_concorrencia_id');
            $table->unsignedInteger('qc_status_id');
            $table->timestamps();
            $table->unsignedInteger('user_id');


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('quadro_de_concorrencia_id')
                ->references('id')->on('quadro_de_concorrencias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('qc_status_id')
                ->references('id')->on('qc_status')
                ->onDelete('cascade')
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
        Schema::dropIfExists('qc_status_log');
    }
}
