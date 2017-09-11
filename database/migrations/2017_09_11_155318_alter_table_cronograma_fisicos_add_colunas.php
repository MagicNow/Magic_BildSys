<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCronogramaFisicosAddColunas extends Migration
{    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cronograma_fisicos', function (Blueprint $table) {
            $table->dropColumn('template_id');
        });

        Schema::table('cronograma_fisicos', function (Blueprint $table) {
            
			$table->date('data_upload');			
            $table->unsignedInteger('template_id')->nullable();
            $table->foreign('template_id')
                ->references('id')
                ->on('template_planilhas')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cronograma_fisicos', function (Blueprint $table) {
            $table->dropColumn('data_upload');
			$table->dropColumn('template_id');
        });

        Schema::table('cronograma_fisicos', function (Blueprint $table) {
            $table->string('template_id');
        });
    }
}
