<?php

use Illuminate\Database\Seeder;

class TemplateEmailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('template_emails')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Solicitação de orçamento - BILD',
                'template' => '<p>Olá [FORNECEDOR_NOME],</p>
                               <p>Você foi selecionado para um Quadro de concorrência, por favor indique os valores para os produtos/serviços abaixo:</p>
                               <p>[TABELA_PRODUTOS]</p>
                               <p>Aguardamos o mais breve possível o retorno deste e-mail</p>
                                ',
                'tags'=> '{"1":{"tag":"[FORNECEDOR_NOME]","nome":"Fornecedor Nome"},"2":{"tag":"[TABELA_PRODUTOS]","nome":"Tabela de Produtos"}}',
                'user_id' => 2,
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 2,
                'nome' => 'Boletim de Medição de serviço',
                'template' => '<p>Olá [FORNECEDOR_NOME],</p>
                               </p>
                                O boletim da medição do seu serviço prestado foi gerado e liberado para faturamento.
                                <br>
                                <br>
                                Baixe o arquivo em anexo, e gere uma Nota fiscal no valor informado.
                               </p>
                               <p>Aguardamos o mais breve possível o retorno deste e-mail</p>
                                ',
                'tags'=> '{"1":{"tag":"[FORNECEDOR_NOME]","nome":"Fornecedor Nome"}}',
                'user_id' => 2,
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 3,
                'nome' => 'Contrato - BILD',
                'template' => '<p>Olá [FORNECEDOR_NOME],</p>
                               <p>
                                O contrato foi gerado e é necessário sua assinatura em todas as folhas para dar seguimento no processo de contratação.
                                <br>
                                <br>
                                Baixe o arquivo em anexo, assine e nos envie.
                               </p>
                               <p>Aguardamos o mais breve possível o retorno deste e-mail</p>
                                ',
                'tags'=> '{"1":{"tag":"[FORNECEDOR_NOME]","nome":"Fornecedor Nome"}}',
                'user_id' => 2,
                'created_at' => date('Y-m-d H::s')
            ]
        ];

        DB::table('template_emails')->insert($items);
        Schema::enableForeignKeyConstraints();

    }
}
