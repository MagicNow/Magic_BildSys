<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsWorkflowReprovacaoMotivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow_reprovacao_motivos', function (Blueprint $table){
            $table->timestamps();
            $table->softDeletes();
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
            $table->dropColumn(['created_at', 'updated_at', 'deleted_at']);
        });
    }
}
