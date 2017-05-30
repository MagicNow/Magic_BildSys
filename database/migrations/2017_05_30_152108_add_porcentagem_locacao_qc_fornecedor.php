<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPorcentagemLocacaoQcFornecedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_fornecedor',function(Blueprint $table){
            $table->decimal('porcentagem_locacao',19,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc_fornecedor',function(Blueprint $table){
            $table->dropColumn('porcentagem_locacao');
        });
    }
}
