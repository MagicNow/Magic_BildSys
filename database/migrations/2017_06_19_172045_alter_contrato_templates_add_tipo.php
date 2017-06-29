<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratoTemplatesAddTipo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_templates', function (Blueprint $table){
            $table->char('tipo', 1)->default('Q')->comment('Q = Qualquer
                M = Material (apenas 1 por sistema)
                A = Acordo (apenas 1 por sistema)');
        });
        $seed = new ContratoTemplateSeed();
        $seed->run();
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_templates', function (Blueprint $table){
            $table->dropColumn('tipo');
        });
    }
}
