<?php

use Illuminate\Database\Seeder;

class WorkflowTipoTableSeeder extends Seeder {

    public function run()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        \Illuminate\Support\Facades\DB::table('workflow_tipos')->delete();
        
        \Illuminate\Support\Facades\DB::table('workflow_tipos')
            ->insert([
                [
                    'id'=> 1,
                    'nome'=> 'Workflow Aprovação de O.C.',
                    'dias_prazo' => 7
                ],
                [
                    'id'=> 2,
                    'nome'=> 'Workflow Validação de Escopo Q.C.',
                    'dias_prazo' => 3
                ],
                [
                    'id'=> 3,
                    'nome'=> 'Workflow Aprovação de Contrato',
                    'dias_prazo' => 7
                ]
            ]);
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

    }

}
