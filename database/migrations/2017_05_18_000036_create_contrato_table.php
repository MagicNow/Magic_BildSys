<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoTable extends Migration
{
    /**
     * Run the migrations.
     * @table contratos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('contrato_status_id');
            $table->unsignedInteger('obra_id');
            $table->unsignedInteger('quadro_de_concorrencia_id')->nullable();
            $table->unsignedInteger('fornecedor_id');
            $table->decimal('valor_total', 19, 2);
            $table->unsignedInteger('contrato_template_id');
            $table->string('arquivo', 255)->nullable();
            $table->nullableTimestamps();

            $table->foreign('contrato_status_id')
                ->references('id')->on('contrato_status')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('quadro_de_concorrencia_id')
                ->references('id')->on('quadro_de_concorrencias')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('contrato_template_id')
                ->references('id')->on('contrato_templates')
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
       Schema::dropIfExists('contratos');
     }
}
