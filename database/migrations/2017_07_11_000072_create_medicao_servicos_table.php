<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoServicosTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicao_servicos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_servicos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('medicao_id');
            $table->integer('qtd_funcionarios')->nullable()->default('0');
            $table->integer('qtd_ajudantes')->nullable()->default('0');
            $table->integer('qtd_outros')->nullable()->default('0');
            $table->decimal('descontos', 19, 2)->nullable();
            $table->text('descricao_descontos')->nullable();
            $table->unsignedInteger('user_id');


            $table->foreign('medicao_id')
                ->references('id')->on('medicoes')
                ->onDelete('cascade')
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
       Schema::dropIfExists('medicao_servicos');
     }
}
