<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlanejamentoAddNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('planejamentos', function (Blueprint $table){
            $table->dropColumn(['created_at']);
            $table->unsignedInteger('planejamento_id')->nullable()->change();
        });
        Schema::table('planejamentos', function (Blueprint $table){
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planejamentos', function (Blueprint $table){
            $table->dropColumn(['created_at','updated_at','deleted_at']);
            $table->unsignedInteger('planejamento_id')->change();
        });
        Schema::table('planejamentos', function (Blueprint $table){
            $table->string('created_at', 45)->nullable();
        });
    }
}
