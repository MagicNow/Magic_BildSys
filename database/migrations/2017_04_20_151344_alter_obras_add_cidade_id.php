<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterObrasAddCidadeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obras', function (Blueprint $table){
            $table->unsignedInteger('cidade_id')->nullable();
            $table->foreign('cidade_id')->references('id')->on('cidades')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obras', function (Blueprint $table){
            $table->dropForeign(['cidade_id']);
            $table->dropColumn('cidade_id');
        });
    }
}
