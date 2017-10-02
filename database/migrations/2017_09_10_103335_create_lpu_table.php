<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLpuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('lpu');
        Schema::create('lpu', function (Blueprint $table) {			
			
            $table->increments('id');
            $table->unsignedInteger('insumo_id');
			$table->string('codigo_insumo',100);
			$table->unsignedInteger('regional_id');
            $table->unsignedInteger('grupo_id');
			$table->unsignedInteger('subgrupo1_id');
			$table->unsignedInteger('subgrupo2_id');
			$table->unsignedInteger('subgrupo3_id');
			$table->unsignedInteger('servico_id');
			$table->decimal('valor_sugerido_anterior', 19, 2);
			$table->decimal('valor_sugerido_atual', 19, 2);
			$table->decimal('valor_contrato', 19, 2);
			$table->decimal('valor_catalogo', 19, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onUpdate('cascade');

            $table->foreign('regional_id')
                ->references('id')->on('regionais')
                ->onUpdate('cascade');
			
			$table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onUpdate('cascade');
			
			$table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onUpdate('cascade');
			
			$table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onUpdate('cascade');
			
			$table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
                ->onUpdate('cascade');
			
			$table->foreign('servico_id')
                ->references('id')->on('servicos')
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
        Schema::dropIfExists('lpu');

    }
}
