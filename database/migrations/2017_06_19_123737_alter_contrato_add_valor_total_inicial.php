<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoAddValorTotalInicial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function($table) {
            $table->renameColumn('valor_total', 'valor_total_atual');
            $table->decimal('valor_total_inicial', 19, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function($table) {
            $table->dropColumn('valor_total_inicial');
            $table->renameColumn('valor_total_atual', 'valor_total');
        });
    }
}
