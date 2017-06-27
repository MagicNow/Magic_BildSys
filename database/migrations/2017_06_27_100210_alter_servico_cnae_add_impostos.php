<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterServicoCnaeAddImpostos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicos_cnae', function($table) {
            $table -> decimal('irrf', 10,2)   -> unsigned() -> nullable() -> default(null);
            $table -> decimal('pis', 10,2)    -> unsigned() -> nullable() -> default(null);
            $table -> decimal('cofins', 10,2) -> unsigned() -> nullable() -> default(null);
            $table -> decimal('inss', 10,2)   -> unsigned() -> nullable() -> default(null);
            $table -> decimal('csll', 10,2)   -> unsigned() -> nullable() -> default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicos_cnae', function($table) {
            $table -> dropColumn('irrf');
            $table -> dropColumn('pis');
            $table -> dropColumn('cofins');
            $table -> dropColumn('inss');
            $table -> dropColumn('csll');
        });
    }
}
