<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFornecedoresAddColumnsNovas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fornecedores', function (Blueprint $table){
            $table->string('nome_socio')->nullable();
            $table->string('nacionalidade_socio')->nullable();
            $table->string('estado_civil_socio')->nullable();
            $table->string('profissao_socio')->nullable();
            $table->string('rg_socio')->nullable();
            $table->string('cpf_socio')->nullable();
            $table->string('endereco_socio')->nullable();
            $table->string('cidade_socio')->nullable();
            $table->string('estado_socio')->nullable();
            $table->string('cep_socio')->nullable();
            $table->string('telefone_socio')->nullable();
            $table->string('celular_socio')->nullable();
            $table->string('email_socio')->nullable();
            $table->string('nome_vendedor')->nullable();
            $table->string('email_vendedor')->nullable();
            $table->string('telefone_vendedor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fornecedores', function (Blueprint $table){
            $table->dropColumn('nome_socio');
            $table->dropColumn('nacionalidade_socio');
            $table->dropColumn('estado_civil_socio');
            $table->dropColumn('profissao_socio');
            $table->dropColumn('rg_socio');
            $table->dropColumn('cpf_socio');
            $table->dropColumn('endereco_socio');
            $table->dropColumn('cidade_socio');
            $table->dropColumn('estado_socio');
            $table->dropColumn('cep_socio');
            $table->dropColumn('telefone_socio');
            $table->dropColumn('celular_socio');
            $table->dropColumn('email_socio');
            $table->dropColumn('nome_vendedor');
            $table->dropColumn('email_vendedor');
            $table->dropColumn('telefone_vendedor');
        });
    }
}
