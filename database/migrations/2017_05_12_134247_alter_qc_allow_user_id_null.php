<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcAllowUserIdNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quadro_de_concorrencias', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('qc_fornecedor', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('qc_item_qc_fornecedor', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
        Schema::table('qc_status_log', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quadro_de_concorrencias', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

        });
        Schema::table('qc_fornecedor', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('qc_item_qc_fornecedor', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('qc_status_log', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->unsignedInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
