<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNcmInsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos', function(Blueprint $table){
            $table->integer('ncm_codigo')->nullable();
            $table->string('ncm_texto')->nullable();
            $table->string('ncm_codigo_texto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos', function(Blueprint $table){
            $table->dropColumn('ncm_codigo');
            $table->dropColumn('ncm_texto');
            $table->dropColumn('ncm_codigo_texto');
        });
    }
}
