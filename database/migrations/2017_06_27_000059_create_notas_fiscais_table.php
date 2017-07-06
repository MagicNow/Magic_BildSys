<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasFiscaisTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'notas_fiscais';

    /**
     * Run the migrations.
     * @table notas_fiscais
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_id')->nullable();
            $table->unsignedInteger('solicitacao_entrega_id')->nullable();
            $table->longText('xml')->nullable();
            $table->string('codigo', 100);
            $table->string('versao', 10);
            $table->string('natureza_operacao', 100)->nullable();
            $table->dateTime('data_emissao')->nullable();
            $table->dateTime('data_saida')->nullable();
            $table->string('cnpj', 20)->nullable();
            $table->string('razao_social')->nullable();
            $table->string('fantasia')->nullable();
            $table->string('cnpj_destinatario', 20)->nullable();
            $table->string('arquivo_nfe')->nullable();

            $table->index(["contrato_id"], 'fk_notas_fiscais_contratos1_idx');

            $table->index(["solicitacao_entrega_id"], 'fk_notas_fiscais_solicitacao_entregas1_idx');


            $table->foreign('contrato_id', 'fk_notas_fiscais_contratos1_idx')
                ->references('id')->on('contratos')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('solicitacao_entrega_id', 'fk_notas_fiscais_solicitacao_entregas1_idx')
                ->references('id')->on('solicitacao_entregas')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->set_schema_table);
     }
}
