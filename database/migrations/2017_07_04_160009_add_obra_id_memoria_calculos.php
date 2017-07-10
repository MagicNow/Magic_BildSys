<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddObraIdMemoriaCalculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memoria_calculos', function (Blueprint $table){
            $table->unsignedInteger('obra_id')->nullable();

            $table->foreign('obra_id')
                ->references('id')
                ->on('obras')
                ->onDelete('set null')
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
        Schema::table('memoria_calculos', function (Blueprint $table){
            $table->dropForeign(['obra_id']);
            $table->dropColumn('obra_id');
        });
    }
}
