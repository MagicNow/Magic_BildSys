<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNomeclaturaMapasTable extends Migration
{
    /**
     * Run the migrations.
     * @table nomeclatura_mapas
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('nomeclatura_mapas');
        Schema::create('nomeclatura_mapas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 255);
            $table->tinyInteger('tipo')->comment('1 = Estrutura (Pré-tipo, Pós tipo, etc)
2 = Pavimento (Subsolo1, Térreo, etc)
3 = Trecho  (Hall de entrada, Apartamento 1, etc)');
            $table->tinyInteger('apenas_cartela')->default('0')->comment('0');
            $table->tinyInteger('apenas_unidade')->default('0');
            $table->smallInteger('largura_visual')->default('100');
        });

        $seeder = new NomeclaturaMapasSeeder();
        $seeder->run();
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists('nomeclatura_mapas');
     }
}
