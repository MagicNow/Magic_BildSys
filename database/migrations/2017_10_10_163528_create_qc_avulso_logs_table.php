<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcAvulsoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('observacao');

            $table->unsignedInteger('qc_status_id')->nullable();

            $table->foreign('qc_status_id')
                ->references('id')
                ->on('qc_status')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        Schema::create('qc_avulso_status_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('qc_status_id');
            $table->unsignedInteger('qc_id');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('qc_status_id')
                ->references('id')
                ->on('qc_status')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('qc_id')
                ->references('id')
                ->on('qc')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
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
            $table->string('status', 50)->default('Em aprovação');
            $table->text('observacao')->nullable()->after('carteira_comprada');

            $table->dropForeign(['qc_status_id']);
            $table->dropColumn('qc_status_id');
        });

        Schema::dropIfExists('qc_avulso_status_log');
    }
}
