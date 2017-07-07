<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitacaoEntregasTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'solicitacao_entregas';

    /**
     * Run the migrations.
     * @table solicitacao_entregas
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('se_status_id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('user_id');
            $table->decimal('valor_total', 19, 2)->nullable();
            $table->tinyInteger('habilita_faturamento')->nullable();
            $table->tinyInteger('aprovado')->nullable();

            $table->index(["contrato_id"], 'fk_solicitacao_entregas_contratos1_idx');

            $table->index(["se_status_id"], 'fk_solicitacao_entregas_se_status1_idx');
            $table->nullableTimestamps();


            $table->foreign('contrato_id', 'fk_solicitacao_entregas_contratos1_idx')
                ->references('id')->on('contratos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('se_status_id', 'fk_solicitacao_entregas_se_status1_idx')
                ->references('id')->on('se_status')
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
       Schema::dropIfExists($this->set_schema_table);
     }
}
