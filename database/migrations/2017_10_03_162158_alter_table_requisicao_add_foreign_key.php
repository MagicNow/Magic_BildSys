<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRequisicaoAddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisicao', function (Blueprint $table){
            $table->dropColumn('status');
        });

        Schema::table('requisicao', function (Blueprint $table){
            $table->unsignedInteger('status_id')->nullable();

            $table->foreign('status_id')
                ->references('id')
                ->on('requisicao_status')
                ->onUpdate('cascade')
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
        Schema::table('requisicao', function (Blueprint $table){
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });

        Schema::table('requisicao', function (Blueprint $table){
            $table->string('status');
        });
    }
}
