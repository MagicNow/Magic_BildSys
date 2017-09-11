<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePlanilhaAddColumnModulo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planilhas', function (Blueprint $table) {
            $table->renameColumn('status', 'modulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planilhas', function (Blueprint $table) {
            $table->dropColumn('modulo');
            $table->string('status')->nullable();
        });
    }
}
