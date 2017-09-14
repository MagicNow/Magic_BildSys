<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotaFiscaisAddColunaStatusAndDataStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->string('status', 50)->nullable();
            $table->datetime('status_data')->nullable();
            $table->unsignedInteger('status_user_id')->nullable();

            $table->foreign('status_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('status_data');
            $table->dropColumn('status_user_id');
        });
    }
}
