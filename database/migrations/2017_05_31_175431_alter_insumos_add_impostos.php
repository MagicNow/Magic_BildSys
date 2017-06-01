<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInsumosAddImpostos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos', function($table) {
            $table->decimal('aliq_irrf', 11, 2)->unsigned()->nullable();
            $table->decimal('aliq_inss', 11, 2)->unsigned()->nullable();
            $table->decimal('aliq_csll', 11, 2)->unsigned()->nullable();
            $table->decimal('aliq_pis', 11, 2)->unsigned()->nullable();
            $table->decimal('aliq_cofins', 11, 2)->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos', function($table) {
            $table->dropColumn([
                'aliq_irrf',
                'aliq_inss',
                'aliq_pis',
                'aliq_cofins',
                'aliq_csll',
            ]);
        });
    }
}
