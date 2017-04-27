<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterObrasAddFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('obras', function (Blueprint $table){
            $table->decimal('area_terreno', 19, 2)->nullable();
            $table->decimal('area_privativa', 19, 2)->nullable();
            $table->decimal('area_construida', 19, 2)->nullable();
            $table->decimal('eficiencia_projeto', 19, 2)->nullable();
            $table->decimal('num_apartamentos', 19, 2)->nullable();
            $table->decimal('num_torres', 19, 2)->nullable();
            $table->decimal('num_pavimento_tipo', 19, 2)->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_cliente')->nullable();
            $table->decimal('indice_bild_pre', 19, 2)->nullable();
            $table->decimal('indice_bild_oi', 19, 2)->nullable();
            $table->string('razao_social')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('inscricao_estadual')->nullable();
            $table->string('endereco_faturamento')->nullable();
            $table->string('endereco_obra')->nullable();
            $table->string('entrega_nota_fisca_e_boleto')->nullable();
            $table->string('adm_obra_nome')->nullable();
            $table->string('adm_obra_email')->nullable();
            $table->string('eng_obra_nome')->nullable();
            $table->string('eng_obra_email')->nullable();
            $table->string('horario_entrega_na_obra')->nullable();
            $table->string('referencias_bancarias')->nullable();
            $table->string('referencias_comerciais')->nullable();
            $table->string('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('obras', function (Blueprint $table){
            $table->dropColumn('area_terreno');
            $table->dropColumn('area_privativa');
            $table->dropColumn('area_construida');
            $table->dropColumn('eficiencia_projeto');
            $table->dropColumn('num_apartamentos');
            $table->dropColumn('num_torres');
            $table->dropColumn('num_pavimento_tipo');
            $table->dropColumn('data_inicio');
            $table->dropColumn('data_cliente');
            $table->dropColumn('indice_bild_pre');
            $table->dropColumn('indice_bild_oi');
            $table->dropColumn('razao_social');
            $table->dropColumn('cnpj');
            $table->dropColumn('inscricao_estadual');
            $table->dropColumn('endereco_faturamento');
            $table->dropColumn('endereco_obra');
            $table->dropColumn('entrega_nota_fisca_e_boleto');
            $table->dropColumn('adm_obra_nome');
            $table->dropColumn('adm_obra_email');
            $table->dropColumn('eng_obra_nome');
            $table->dropColumn('eng_obra_email');
            $table->dropColumn('horario_entrega_na_obra');
            $table->dropColumn('referencias_bancarias');
            $table->dropColumn('referencias_comerciais');
            $table->dropColumn('logo');
        });
    }
}
