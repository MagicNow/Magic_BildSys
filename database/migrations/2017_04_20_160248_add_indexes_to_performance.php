<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos', function (Blueprint $table){
            $table->index('codigo');
        });

        Schema::table('servicos', function (Blueprint $table){
            $table->index('codigo');
        });

        Schema::table('grupos', function (Blueprint $table){
            $table->index('codigo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos', function (Blueprint $table){
            $table->dropIndex(['codigo']);
        });

        Schema::table('servicos', function (Blueprint $table){
            $table->dropIndex(['codigo']);
        });

        Schema::table('grupos', function (Blueprint $table){
            $table->dropIndex(['codigo']);
        });
    }
}
