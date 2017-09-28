<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAndDropTablesMascaraPadrao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('mascara_padrao_insumos');

        Schema::dropIfExists('mascara_padrao');
        Schema::enableForeignKeyConstraints();

        Schema::create('mascara_padrao', function(Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->timestamps();
        });

        Schema::create('mascara_padrao_estruturas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->decimal('coeficiente', 19, 6)->nullable();
            $table->decimal('indireto', 19, 6)->nullable();

            $table->unsignedInteger('mascara_padrao_id');
            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('servico_id');

            $table->foreign('mascara_padrao_id')
                ->references('id')->on('mascara_padrao')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('mascara_padrao_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('mascara_padrao_estrutura_id');
            $table->string('codigo_estruturado', 45);
            $table->decimal('coeficiente', 19, 6)->nullable();
            $table->decimal('indireto', 19, 6)->nullable();
            $table->unsignedInteger('insumo_id');
            $table->timestamps();

            $table->foreign('mascara_padrao_estrutura_id')
                ->references('id')->on('mascara_padrao_estruturas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('mascara_padrao_insumos');
        Schema::dropIfExists('mascara_padrao_estruturas');
        Schema::dropIfExists('mascara_padrao');
        Schema::enableForeignKeyConstraints();

        Schema::table('mascara_padrao', function (Blueprint $table) {

            $table->string('codigo', 45);
            $table->decimal('coeficiente', 19, 6)->nullable();

            $table->unsignedInteger('grupo_id')->nullable();
            $table->unsignedInteger('subgrupo1_id')->nullable();
            $table->unsignedInteger('subgrupo2_id')->nullable();
            $table->unsignedInteger('subgrupo3_id')->nullable();
            $table->unsignedInteger('servico_id')->nullable();

            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('mascara_padrao_insumos', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('mascara_padrao_id');
            $table->string('codigo_estruturado', 45);
            $table->unsignedInteger('insumo_id');
            $table->decimal('coeficiente', 19, 6)->nullable();
            $table->decimal('indireto', 19, 2)->nullable();

            $table->unsignedInteger('grupo_id');
            $table->unsignedInteger('subgrupo1_id');
            $table->unsignedInteger('subgrupo2_id');
            $table->unsignedInteger('subgrupo3_id');
            $table->unsignedInteger('servico_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('mascara_padrao_id')
                ->references('id')->on('mascara_padrao')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('insumo_id')
                ->references('id')->on('insumos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('grupo_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo1_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo2_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subgrupo3_id')
                ->references('id')->on('grupos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('servico_id')
                ->references('id')->on('servicos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
