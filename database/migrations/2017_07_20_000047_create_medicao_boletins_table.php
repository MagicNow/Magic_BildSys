<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoBoletinsTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicao_boletins
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_boletins', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('medicao_boletim_status_id');
            $table->text('obs')->nullable();
            $table->unsignedInteger('user_id');


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('contrato_id')
                ->references('id')->on('contratos')
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
       Schema::dropIfExists('medicao_boletins');
     }
}
