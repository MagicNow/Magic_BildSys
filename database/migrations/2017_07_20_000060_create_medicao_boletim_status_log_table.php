<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoBoletimStatusLogTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicao_boletim_status_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_boletim_status_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('medicao_boletim_id');
            $table->unsignedInteger('medicao_boletim_status_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('medicao_boletim_id')
                ->references('id')->on('medicao_boletins')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('medicao_boletim_status_id')
                ->references('id')->on('medicao_boletim_status')
                ->onDelete('restrict')
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
       Schema::dropIfExists('medicao_boletim_status_log');
     }
}
