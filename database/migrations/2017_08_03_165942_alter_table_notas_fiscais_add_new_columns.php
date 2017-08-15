<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNotasFiscaisAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->string('serie', 30)->nullable();
            $table->tinyInteger('tipo_entrada_saida')->nullable();
            $table->string("protocolo")->nullable();
            $table->string("remetente_inscricao_estadual")->nullable();
            $table->string("remetente_inscricao_estadual_sub")->nullable();
            $table->string("remetente_endereco")->nullable();
            $table->string("remetente_numero", 20)->nullable();
            $table->string("remetente_bairro")->nullable();
            $table->string("remetente_cep")->nullable();
            $table->string("remetente_cidade")->nullable();
            $table->string("remetente_uf", 2)->nullable();
            $table->string("remetente_fone_fax")->nullable();
            $table->string("destinatario_nome")->nullable();
            $table->string("destinatario_endereco")->nullable();
            $table->string("destinatario_numero", 20)->nullable();
            $table->string("destinatario_bairro")->nullable();
            $table->string("destinatario_cep")->nullable();
            $table->string("destinatario_cidade")->nullable();
            $table->string("destinatario_uf", 2)->nullable();
            $table->string("destinatario_fone_fax")->nullable();
            $table->string("destinatario_inscricao_estadual")->nullable();
            $table->string("destinatario_inscricao_estadual_sub")->nullable();
            $table->decimal("base_calculo_icms", 19, 2)->nullable();
            $table->decimal("valor_icms", 19, 2)->nullable();
            $table->decimal("base_calculo_icms_sub", 19, 2)->nullable();
            $table->decimal("valor_icms_sub", 19, 2)->nullable();
            $table->decimal("valor_imposto_importacao", 19, 2)->nullable();
            $table->decimal("valor_icms_uf_remetente", 19, 2)->nullable();
            $table->decimal("valor_fcp", 19, 2)->nullable();
            $table->decimal("valor_pis", 19, 2)->nullable();
            $table->decimal("valor_total_produtos", 19, 2)->nullable();
            $table->decimal("valor_frete", 19, 2)->nullable();
            $table->decimal("valor_seguro", 19, 2)->nullable();
            $table->decimal("desconto", 19, 2)->nullable();
            $table->decimal("outras_despesas", 19, 2)->nullable();
            $table->decimal("valor_total_ipi", 19, 2)->nullable();
            $table->decimal("valor_icms_uf_destinatario", 19, 2)->nullable();
            $table->decimal("valor_total_tributos", 19, 2)->nullable();
            $table->decimal("valor_confins", 19, 2)->nullable();
            $table->decimal("valor_total_nota", 19, 2)->nullable();
            $table->string("transportadora_nome")->nullable();
            $table->tinyInteger('frete_por_conta')->nullable();
            $table->string("codigo_antt", 30)->nullable();
            $table->string("placa_veiculo", 20)->nullable();
            $table->string("veiculo_uf", 2)->nullable();
            $table->string("transportadora_cnpj")->nullable();
            $table->string("transportadora_endereco")->nullable();
            $table->string("transportadora_municipio")->nullable();
            $table->string("transportadora_uf", 2)->nullable();
            $table->string("transportadora_inscricao")->nullable();
            $table->integer("transportadora_quantidade")->nullable();
            $table->string("especie")->nullable();
            $table->string("marca")->nullable();
            $table->string("numeracao")->nullable();
            $table->decimal("peso_bruto", 19, 2)->nullable();
            $table->decimal("peso_liquido", 19, 2)->nullable();
            $table->text("dados_adicionais")->nullable();
        });



        Schema::table("nota_fiscal_itens", function(Blueprint $table) {
            $table->decimal("aliquota_icms", 19, 2)->nullable();
            $table->decimal("aliquota_ipi", 19, 2)->nullable();
            $table->string("cfop", 30)->nullable();
            $table->renameColumn('valor_tributavel', 'base_calculo_icms');
            $table->renameColumn('ipi', 'valor_ipi');
            $table->renameColumn('icms', 'valor_icms');
            $table->renameColumn('cofins', 'valor_cofins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->dropColumn('serie');
            $table->dropColumn('tipo_entrada_saida');
            $table->dropColumn("protocolo");
            $table->dropColumn("remetente_inscricao_estadual");
            $table->dropColumn("remetente_inscricao_estadual_sub");
            $table->dropColumn("remetente_endereco");
            $table->dropColumn("remetente_numero", 20);
            $table->dropColumn("remetente_bairro");
            $table->dropColumn("remetente_cep");
            $table->dropColumn("remetente_cidade");
            $table->dropColumn("remetente_uf", 2);
            $table->dropColumn("remetente_fone_fax");
            $table->dropColumn("destinatario_nome");
            $table->dropColumn("destinatario_endereco");
            $table->dropColumn("destinatario_numero", 20);
            $table->dropColumn("destinatario_bairro");
            $table->dropColumn("destinatario_cep");
            $table->dropColumn("destinatario_cidade");
            $table->dropColumn("destinatario_uf", 2);
            $table->dropColumn("destinatario_fone_fax");
            $table->dropColumn("destinatario_inscricao_estadual");
            $table->dropColumn("destinatario_inscricao_estadual_sub");
            $table->dropColumn("base_calculo_icms", 19, 2);
            $table->dropColumn("valor_icms", 19, 2);
            $table->dropColumn("base_calculo_icms_sub", 19, 2);
            $table->dropColumn("valor_icms_sub", 19, 2);
            $table->dropColumn("valor_imposto_importacao", 19, 2);
            $table->dropColumn("valor_icms_uf_remetente", 19, 2);
            $table->dropColumn("valor_fcp", 19, 2);
            $table->dropColumn("valor_pis", 19, 2);
            $table->dropColumn("valor_total_produtos", 19, 2);
            $table->dropColumn("valor_frete", 19, 2);
            $table->dropColumn("valor_seguro", 19, 2);
            $table->dropColumn("desconto", 19, 2);
            $table->dropColumn("outras_despesas", 19, 2);
            $table->dropColumn("valor_total_ipi", 19, 2);
            $table->dropColumn("valor_icms_uf_destinatario", 19, 2);
            $table->dropColumn("valor_total_tributos", 19, 2);
            $table->dropColumn("valor_confins", 19, 2);
            $table->dropColumn("valor_total_nota", 19, 2);
            $table->dropColumn("transportadora_nome");
            $table->dropColumn('frete_por_conta');
            $table->dropColumn("codigo_antt", 30);
            $table->dropColumn("placa_veiculo", 20);
            $table->dropColumn("veiculo_uf", 2);
            $table->dropColumn("transportadora_cnpj");
            $table->dropColumn("transportadora_endereco");
            $table->dropColumn("transportadora_municipio");
            $table->dropColumn("transportadora_uf", 2);
            $table->dropColumn("transportadora_inscricao");
            $table->dropColumn("transportadora_quantidade");
            $table->dropColumn("especie");
            $table->dropColumn("marca");
            $table->dropColumn("numeracao");
            $table->dropColumn("peso_bruto", 19, 2);
            $table->dropColumn("peso_liquido", 19, 2);
            $table->dropColumn("dados_adicionais", 19, 2);
        });

        Schema::table("nota_fiscal_itens", function(Blueprint $table) {
            $table->dropColumn("aliquota_icms");
            $table->dropColumn("aliquota_ipi");
            $table->dropColumn("cfop");
            $table->renameColumn('base_calculo_icms', 'valor_tributavel');
            $table->renameColumn('valor_ipi', 'ipi');
            $table->renameColumn('valor_icms', 'icms');
            $table->renameColumn('valor_cofins', 'cofins');
        });

    }
}
