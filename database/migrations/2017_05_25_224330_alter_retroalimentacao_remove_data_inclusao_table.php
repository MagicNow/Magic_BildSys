<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRetroalimentacaoRemoveDataInclusaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retroalimentacao_obras', function (Blueprint $table) {
           $table->dropColumn('data_inclusao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retroalimentacao_obras', function (Blueprint $table) {
            $table->date('data_inclusao');
        });
    }
}
