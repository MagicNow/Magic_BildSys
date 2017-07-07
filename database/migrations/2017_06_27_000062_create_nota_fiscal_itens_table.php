<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotaFiscalItensTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'nota_fiscal_itens';

    /**
     * Run the migrations.
     * @table nota_fiscal_itens
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('nota_fiscal_id');
            $table->integer('ncm')->nullable();
            $table->string('codigo_produto')->nullable();
            $table->string('ean')->nullable();
            $table->decimal('qtd', 19, 2)->nullable();
            $table->decimal('valor_unitario', 19, 2)->nullable();
            $table->decimal('valor_total', 19, 2)->nullable();
            $table->string('unidade', 15)->nullable();
            $table->decimal('valor_tributavel', 19, 2)->nullable();
            $table->decimal('icms', 19, 2)->nullable();
            $table->decimal('ipi', 19, 2)->nullable()->comment('	');
            $table->decimal('cofins', 19, 2)->nullable();

            $table->index(["nota_fiscal_id"], 'fk_nota_fiscal_itens_notas_fiscais1_idx');


            $table->foreign('nota_fiscal_id', 'fk_nota_fiscal_itens_notas_fiscais1_idx')
                ->references('id')->on('notas_fiscais')
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
       Schema::dropIfExists($this->set_schema_table);
     }
}
