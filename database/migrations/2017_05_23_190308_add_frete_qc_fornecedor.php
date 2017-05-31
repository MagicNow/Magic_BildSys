<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFreteQcFornecedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qc_fornecedor', function (Blueprint $table){
            $table->string('tipo_frete',3)->nullable();
            $table->decimal('valor_frete',19,2)->nullable();
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
            $table->dropColumn('tipo_frete');
            $table->dropColumn('valor_frete');
        });
    }
}
