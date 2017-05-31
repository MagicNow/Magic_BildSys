<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNcmContratoItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_itens', function(Blueprint $table){
            $table->integer('ncm_codigo')->nullable();
            $table->string('ncm_texto',45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_itens', function(Blueprint $table){
            $table->dropColumn('ncm_codigo');
            $table->dropColumn('ncm_texto');
        });
    }
}
