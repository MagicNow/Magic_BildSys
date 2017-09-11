<?php

use Illuminate\Database\Seeder;

class TemplatePlanilhasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        \Illuminate\Support\Facades\DB::table('template_planilhas')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Orçamento inicial',
				'modulo' => 'Orçamento inicial',
				'colunas' => '{"1":"codigo_insumo","2":"descricao","3":"unidade_sigla","4":"coeficiente","5":"indireto","9":"terreo_externo_solo","10":"terreo_externo_estrutura","11":"primeiro_pavimento","12":"segundo_ao_penultimo","13":"atico","15":"reservatorio","16":"cobertura_ultimo_piso","17":"qtd_total","18":"preco_unitario","19":"preco_total","20":"referencia_preco","21":"obs","22":"porcentagem_orcamento"}'
            ],
			[
                'id' => 2,
                'nome' => 'Planejamento',
				'modulo' => 'Planejamento',
				'colunas' => '["resumo","tarefa","prazo","data","data_fim"]'
            ],
			[
                'id' => 3,
                'nome' => 'Plano Diretor',
				'modulo' => 'Cronograma Fisicos',
				'colunas' => '["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]'
            ],
			[
                'id' => 4,
                'nome' => 'Plano Trabalho',
				'modulo' => 'Cronograma Fisicos',
				'colunas' => '["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]'
            ],
			[
                'id' => 5,
                'nome' => 'Tendência Diretor',
				'modulo' => 'Cronograma Fisicos',
				'colunas' => '["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]'
            ],
			[
                'id' => 6,
                'nome' => 'Tendência Trabalho',
				'modulo' => 'Cronograma Fisicos',
				'colunas' => '["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]'
            ],
			[
                'id' => 7,
                'nome' => 'Tendência Real',
				'modulo' => 'Cronograma Fisicos',
				'colunas' => '["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]'
            ],
			[
                'id' => 8,
                'nome' => 'Orçamento/CLO/BD',
				'modulo' => 'Cronograma Fisicos',
				'colunas' => '["custo","resumo","torre","pavimento","tarefa","critica","data_inicio","data_termino","concluida"]'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\TemplatePlanilha::create($item);
        }
    }
}
