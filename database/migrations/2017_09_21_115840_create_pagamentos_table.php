<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     * @table pagamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('obra_id');
            $table->bigInteger('numero_documento');
            $table->unsignedInteger('fornecedor_id');
            $table->date('data_emissao');
            $table->decimal('valor', 19, 2)->nullable();
            $table->unsignedInteger('pagamento_condicao_id');
            $table->unsignedInteger('documento_tipo_id');
            $table->unsignedInteger('notas_fiscal_id')->nullable();
            $table->tinyInteger('enviado_integracao')->default('0');
            $table->tinyInteger('integrado')->default('0');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('notas_fiscal_id')
                ->references('id')->on('notas_fiscais')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('pagamento_condicao_id')
                ->references('id')->on('pagamento_condicoes')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('documento_tipo_id')
                ->references('id')->on('documento_tipos')
                ->onDelete('cascade')
                ->onUpdate('restrict');

            $table->foreign('contrato_id')
                ->references('id')->on('contratos')
                ->onDelete('restrict')
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
        Schema::dropIfExists('pagamentos');
    }
}

