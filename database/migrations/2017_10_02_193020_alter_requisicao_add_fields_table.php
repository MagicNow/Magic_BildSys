<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRequisicaoAddFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requisicao', function (Blueprint $table) {
            $table->string('local', 50)->nullable();
            $table->string('torre', 50)->nullable();
            $table->string('pavimento', 50)->nullable();
            $table->string('trecho', 50)->nullable();
            $table->string('andar', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requisicao', function (Blueprint $table) {
            $table->dropColumn('local');
            $table->dropColumn('torre');
            $table->dropColumn('pavimento');
            $table->dropColumn('trecho');
            $table->dropColumn('andar');
        });
    }
}
