<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCteAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ctes', function (Blueprint $table) {
                $table->string("icms_cst", 30)->nullable();
                $table->decimal("base_calculo_icms", 19, 2)->nullable();
                $table->decimal("aliquota_icms", 19, 2)->nullable();
                $table->decimal("valor_icms", 19, 2)->nullable();
                $table->string("RNTRC", 50)->nullable();
                $table->date("data_previsao")->nullable();
                $table->string("seguradora_responsavel", 10)->nullable();
                $table->string("seguradora_nome", 100)->nullable();
                $table->string("seguradora_apolice", 100)->nullable();
                $table->float("seguradora_valor", 19, 2)->nullable();
                $table->string("unidade_1", 10)->nullable();
                $table->string("tipo_medida_1", 50)->nullable();
                $table->float("quantidade_carga_1", 19, 2)->nullable();
                $table->string("unidade_2", 10)->nullable();
                $table->string("tipo_medida_2", 50)->nullable();
                $table->float("quantidade_carga_2", 19, 2)->nullable();
                $table->string("unidade_3", 10)->nullable();
                $table->string("tipo_medida_3", 50)->nullable();
                $table->float("quantidade_carga_3", 19, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ctes', function (Blueprint $table) {
            $table->dropColumn("icms_cst");
            $table->dropColumn("base_calculo_icms");
            $table->dropColumn("aliquota_icms");
            $table->dropColumn("valor_icms");
            $table->dropColumn("RNTRC");
            $table->dropColumn("data_previsao");
            $table->dropColumn("seguradora_responsavel");
            $table->dropColumn("seguradora_nome");
            $table->dropColumn("seguradora_apolice");
            $table->dropColumn("seguradora_valor");
            $table->dropColumn("unidade_1");
            $table->dropColumn("tipo_medida_1");
            $table->dropColumn("quantidade_carga_1");
            $table->dropColumn("unidade_2");
            $table->dropColumn("tipo_medida_2");
            $table->dropColumn("quantidade_carga_2");
            $table->dropColumn("unidade_3");
            $table->dropColumn("tipo_medida_3");
            $table->dropColumn("quantidade_carga_3");
        });
    }
}
