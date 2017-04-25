<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcItensTable extends Migration
{
    /**
     * Run the migrations.
     * @table qc_itens
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_itens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('quadro_de_concorrencia_id');
            $table->decimal('qtd', 19, 2);
            $table->unsignedInteger('insumos_id');
            $table->timestamps();


            $table->foreign('quadro_de_concorrencia_id')
                ->references('id')->on('quadro_de_concorrencias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumos_id')
                ->references('id')->on('insumos')
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
       Schema::dropIfExists('qc_itens');
     }
}
