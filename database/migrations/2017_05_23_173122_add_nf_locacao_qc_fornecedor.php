<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNfLocacaoQcFornecedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_fornecedor', function (Blueprint $table){
            $table->boolean('nf_locacao')->nullable()->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc_fornecedor', function (Blueprint $table){
            $table->dropColumn('nf_locacao');
        });
    }
}
