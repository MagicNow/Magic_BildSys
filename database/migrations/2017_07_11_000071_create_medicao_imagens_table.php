<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicaoImagensTable extends Migration
{
    /**
     * Run the migrations.
     * @table medicao_imagens
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_imagens', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('di');
            $table->unsignedInteger('medicao_id');
            $table->string('imagem', 255);
            $table->timestamp('created_at');


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
       Schema::dropIfExists('medicao_imagens');
     }
}
