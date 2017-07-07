<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdenacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memoria_calculo_blocos', function (Blueprint $table){
            $table->integer('ordem_linha')->nullable();
            $table->integer('ordem_bloco')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memoria_calculo_blocos', function (Blueprint $table){
            $table->dropColumn('ordem_linha');
            $table->dropColumn('ordem_bloco');
        });
    }
}
