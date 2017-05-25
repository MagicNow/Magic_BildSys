<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTemplateContratoAddCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contrato_templates', function (Blueprint $table){
            $table->longText('campos_extras');
        });
        Schema::table('contratos', function (Blueprint $table){
            $table->longText('campos_extras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contrato_templates', function (Blueprint $table){
            $table->dropColumn('campos_extras');
        });
        Schema::table('contratos', function (Blueprint $table){
            $table->dropColumn('campos_extras');
        });
    }
}
