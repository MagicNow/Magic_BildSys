<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcTableChangeCarteiraIdForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc',function (Blueprint $table){
            $table->dropForeign(['carteira_id']);
            $table->foreign('carteira_id')
                ->references('id')
                ->on('qc_avulso_carteiras')
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
        Schema::table('qc',function (Blueprint $table){
            $table->dropForeign(['carteira_id']);
            $table->foreign('carteira_id')
                ->references('id')
                ->on('carteiras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
