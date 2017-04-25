<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcFornecedorTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_fornecedor
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_fornecedor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('quadro_de_concorrencia_id');
            $table->unsignedInteger('fornecedor_id');
            $table->unsignedInteger('user_id');
            $table->integer('rodada')->nullable()->default('1');
            $table->decimal('porcentagem_material', 19, 2)->nullable();
            $table->decimal('porcentagem_servico', 19, 2)->nullable();
            $table->decimal('porcentagem_faturamento_direto', 19, 2)->nullable();
            $table->unsignedInteger('desistencia_motivo_id')->nullable();
            $table->text('desistencia_texto')->nullable();
            $table->timestamps();

            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('quadro_de_concorrencia_id')
                ->references('id')->on('quadro_de_concorrencias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('fornecedor_id')
                ->references('id')->on('fornecedores')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('desistencia_motivo_id')
                ->references('id')->on('desistencia_motivos')
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
       Schema::dropIfExists('qc_fornecedor');
     }
}
