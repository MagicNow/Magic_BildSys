<?php

use Illuminate\Database\Seeder;

class WorkflowTipoTableSeeder extends Seeder {

    public function run()
    {

        \Illuminate\Support\Facades\DB::table('workflow_tipos')
            ->insert([
                [
                    'id'=> 1,
                    'nome'=> 'Workflow Aprovação de OC',
                    'dias_prazo' => 7
                ]
            ]);

    }

}
