<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQcFornecedorAddNfMaterialNfServico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_fornecedor', function(Blueprint $table) {
            $table->boolean('nf_material')->nullable()->default(NULL);
            $table->boolean('nf_servico')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc_fornecedor', function(Blueprint $table) {
            $table->dropColumn('nf_material', 'nf_servico');
        });
    }
}
