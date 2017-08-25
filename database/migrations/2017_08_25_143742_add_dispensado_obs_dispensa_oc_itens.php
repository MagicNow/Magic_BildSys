<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDispensadoObsDispensaOcItens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordem_de_compra_itens', function (Blueprint $table){
            $table->timestamp('data_dispensa')->nullable();
            $table->string('obs_dispensa')->nullable();
            $table->unsignedInteger('user_id_dispensa')->nullable();
            
            $table->foreign('user_id_dispensa')
                ->references('id')
                ->on('users')
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
        Schema::table('ordem_de_compra_itens', function (Blueprint $table){
            $table->dropForeign(['user_id_dispensa']);
            $table->dropColumn('user_id_dispensa');
            $table->dropColumn('data_dispensa');
            $table->dropColumn('obs_dispensa');
        });
    }
}
