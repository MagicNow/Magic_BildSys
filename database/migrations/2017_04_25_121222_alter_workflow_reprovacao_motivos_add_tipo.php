<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWorkflowReprovacaoMotivosAddTipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow_reprovacao_motivos', function (Blueprint $table){
            $table->unsignedInteger('workflow_tipo_id')->nullable();

            $table->foreign('workflow_tipo_id')
                ->references('id')
                ->on('workflow_tipos')
                ->onUpdate('cascade')
                ->onCascade('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workflow_reprovacao_motivos', function (Blueprint $table){
            $table->dropForeign(['workflow_tipo_id']);
            $table->dropColumn('workflow_tipo_id');
        });
    }
}
