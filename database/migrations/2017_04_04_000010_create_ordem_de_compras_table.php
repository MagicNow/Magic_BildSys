<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdemDeComprasTable extends Migration
{
    /**
     * Run the migrations.
     * @table ordem_de_compras
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordem_de_compras', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('oc_status_id');
            $table->unsignedInteger('obra_id');
            $table->tinyInteger('aprovado')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unsignedInteger('user_id');


            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            
            $table->foreign('oc_status_id')
                ->references('id')->on('oc_status')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('obra_id')
                ->references('id')->on('obras')
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
       Schema::dropIfExists('ordem_de_compras');
     }
}
