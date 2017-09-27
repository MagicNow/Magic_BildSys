<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcApproveColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc', function (Blueprint $table){
            $table->unsignedInteger('user_id')->nullable()->after('carteira_comprada');     
            $table->text('observacao')->nullable()->after('carteira_comprada');     

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
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
        Schema::table('qc', function (Blueprint $table){
            $table->dropForeign(['user_id']);

            $table->dropColumn(['user_id']);
            $table->dropColumn(['observacao']);
        });
    }
}
