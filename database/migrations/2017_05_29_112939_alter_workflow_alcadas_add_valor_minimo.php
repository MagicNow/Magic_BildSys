<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWorkflowAlcadasAddValorMinimo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow_alcadas', function (Blueprint $table) {
            $table->decimal('valor_minimo', 19, 2)->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workflow_alcadas', function (Blueprint $table) {
            $table->dropColumn('valor_minimo');
        });
    }
}
