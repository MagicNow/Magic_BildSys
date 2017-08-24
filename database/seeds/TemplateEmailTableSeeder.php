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
            ],
            [
                'id' => 4,
                'nome' => 'Conta de acesso no sistema da Bild Desenvolvimento Imobiliário',
                'template' => '<p>Olá [FORNECEDOR_NOME],</p>
                               <p>
                                Uma conta de acesso no sistema da Bild Desenvolvimento Imobiliário foi cadastrada para você
                                <br><br>
                                Você pode cadastrar com os dados:
                                <br><br>
                                Email: [FORNECEDOR_EMAIL]
                                <br>
                                Senha: [FORNECEDOR_SENHA]
                               </p>
                               <p>Atenciosamente, Bild Desenvolvimento Imobiliário</p>
                                ',
                'tags'=> '{"1":{"tag":"[FORNECEDOR_NOME]","nome":"Fornecedor Nome"},"2":{"tag":"[FORNECEDOR_EMAIL]","nome":"Fornecedor Email"},"3":{"tag":"[FORNECEDOR_SENHA]","nome":"Fornecedor Senha"}}',
                'user_id' => 2,
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 5,
                'nome' => 'Agradecimento Participação - BILD',
                'template' => '<p>Olá [FORNECEDOR_NOME],</p>
                               <p>Agradecemos sua participação no nosso Quadro de Concorrência.</p>
                               <p>Atenciosamente, Bild Desenvolvimento Imobiliário</p>
                                ',
                'tags'=> '{"1":{"tag":"[FORNECEDOR_NOME]","nome":"Fornecedor Nome"}}',
                'user_id' => 2,
                'created_at' => date('Y-m-d H::s')
            ],
            [
                'id' => 6,
                'nome' => 'Quadro de Concorrência - BILD',
                'template' => '<p>Olá [FORNECEDOR_NOME],</p>
                               <p>Existe um Quadro de Concorrência e você foi convidado à participar</p>
                               <p>Agradecemos antecipadamente pela sua atenção!</p>
                               <p>Atenciosamente, Bild Desenvolvimento Imobiliário</p>
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