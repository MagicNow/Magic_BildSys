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

            $table->unsignedInteger('status')->defualt(1)->change();

            $table->foreign('status')
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

            $table->dropForeign(['status']);

            $table->string('status',50)->change();
        });
    }
}
