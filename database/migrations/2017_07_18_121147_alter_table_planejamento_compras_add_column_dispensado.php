<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePlanejamentoComprasAddColumnDispensado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planejamento_compras', function (Blueprint $table) {
            $table->boolean('dispensado')->default(0);
            $table->unsignedInteger('user_id_dispensa')->nullable();
            $table->dateTime('data_dispensa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planejamento_compras', function (Blueprint $table) {
            $table->dropColumn('dispensado');
            $table->dropColumn('user_id_dispensa');
            $table->dropColumn('data_dispensa');
        });
    }
}
