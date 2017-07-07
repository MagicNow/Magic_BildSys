<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeStatusLogTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'se_status_log';

    /**
     * Run the migrations.
     * @table se_status_log
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('solicitacao_entrega_id');
            $table->unsignedInteger('se_status_id');
            $table->timestamp('created_at')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->index(["se_status_id"], 'fk_se_status_log_se_status1_idx');

            $table->index(["solicitacao_entrega_id"], 'fk_se_status_log_solicitacao_entregas1_idx');


            $table->foreign('se_status_id', 'fk_se_status_log_se_status1_idx')
                ->references('id')->on('se_status')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('solicitacao_entrega_id', 'fk_se_status_log_solicitacao_entregas1_idx')
                ->references('id')->on('solicitacao_entregas')
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
