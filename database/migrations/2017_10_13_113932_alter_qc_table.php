<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc', function (Blueprint $table) {
            $table->dropColumn('numero_contrato');
            $table->string('numero_contrato_mega')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc', function (Blueprint $table) {
            $table->string('numero_contrato')->default(0);
            $table->dropColumn('numero_contrato_mega');
        });
    }
}
