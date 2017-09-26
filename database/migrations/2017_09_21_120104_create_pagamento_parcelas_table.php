<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagamentoParcelasTable extends Migration
{
    /**
     * Run the migrations.
     * @table pagamento_parcelas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamento_parcelas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('valor', 19, 2);
            $table->unsignedInteger('pagamento_id');
            $table->string('numero_documento', 20)->nullable();
            $table->date('data_vencimento');
            $table->decimal('percentual_juro_mora', 5, 2)->nullable();
            $table->decimal('valor_juro_mora', 19, 2)->nullable()->comment('	');
            $table->decimal('percentual_multa', 5, 2)->nullable();
            $table->decimal('valor_multa', 19, 2)->nullable();
            $table->date('data_base_multa')->nullable();
            $table->decimal('percentual_desconto', 5, 2)->nullable();
            $table->decimal('valor_desconto', 19, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('pagamento_id', 'fk_pagamento_parcelas_pagamentos1_idx')
                ->references('id')->on('pagamentos')
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
        Schema::dropIfExists('pagamento_parcelas');
    }
}

