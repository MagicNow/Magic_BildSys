<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoBoletimMedicaoServicoTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicao_boletim_medicao_servico
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_boletim_medicao_servico', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('medicao_boletim_id');
            $table->unsignedInteger('medicao_servico_id');


            $table->foreign('medicao_boletim_id', 'fk_medicao_boletins_has_medicao_servicos_medicao_boletins1_idx')
                ->references('id')->on('medicao_boletins')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('medicao_servico_id', 'fk_medicao_boletins_has_medicao_servicos_medicao_servicos1_idx')
                ->references('id')->on('medicao_servicos')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('medicao_boletim_medicao_servico');
     }
}
