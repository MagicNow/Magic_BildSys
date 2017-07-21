<?php

use Illuminate\Database\Seeder;

class WorkflowTipoTableSeeder extends Seeder {

    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        \Illuminate\Support\Facades\DB::table('workflow_tipos')->delete();
        \Illuminate\Support\Facades\DB::table('workflow_tipos')->truncate();

        \Illuminate\Support\Facades\DB::table('workflow_tipos')
            ->insert([
                [
                    'id'               => 1,
                    'nome'             => 'Workflow Aprovação de O.C.',
                    'dias_prazo'       => 7,
                    'usa_valor_minimo' => 0,
                ], [
                    'id'               => 2,
                    'nome'             => 'Workflow Validação de Escopo Q.C.',
                    'dias_prazo'       => 3,
                    'usa_valor_minimo' => 0,
                ], [
                    'id'               => 3,
                    'nome'             => 'Workflow Aprovação de Contrato',
                    'dias_prazo'       => 7,
                    'usa_valor_minimo' => 1,
                ], [
                    'id'               => 4,
                    'nome'             => 'Workflow Aprovação de Itens de Contrato',
                    'dias_prazo'       => 7,
                    'usa_valor_minimo' => 1,
                ], [
                    'id'               => 5,
                    'nome'             => 'Workflow Aprovação de Solicitação de Entrega',
                    'dias_prazo'       => 3,
                    'usa_valor_minimo' => 0,
                ], [
                    'id'               => 6,
                    'nome'             => 'Workflow Aprovação de Medição',
                    'dias_prazo'       => 5,
                    'usa_valor_minimo' => 0,
                ]

            ]);
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

    }

}
