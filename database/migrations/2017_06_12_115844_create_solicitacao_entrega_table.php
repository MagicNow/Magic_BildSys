<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitacaoEntregaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacao_entregas', function($table) {
            $table->increments('id');
            $table->unsignedInteger('contrato_id');
            $table->unsignedInteger('user_id');
            $table->decimal('valor_total', 19, 2);
            $table->boolean('habilita_faturamento')->default(0);
            $table->boolean('aprovado')->default(NULL)->nullable();
            $table->timestamps();

            $table->foreign('contrato_id')
                ->references('id')
                ->on('contratos');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacao_entregas');
    }
}
