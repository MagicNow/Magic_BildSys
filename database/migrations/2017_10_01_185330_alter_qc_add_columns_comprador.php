<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcAddColumnsComprador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc', function (Blueprint $table) {
            $table->dropColumn('data_fechamento');
        });

        Schema::table('qc', function (Blueprint $table) {
            $table->date('data_fechamento')->nullable()->after('user_id');
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
            $table->date('data_fechamento');
        });
    }
}
