<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;


class MedicaoBoletimStatusTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('medicao_boletim_status')->truncate();

        DB::table('medicao_boletim_status')->insert([[
            'id' => 1,
            'nome' => 'Pendente',
            'cor' => '#DEA447'
        ], [
            'id' => 2,
            'nome' => 'Aguardando Nota',
            'cor' => '#CCCCCC'
        ], [
            'id' => 3,
            'nome' => 'Nota Recebida',
            'cor' => '#008d4c'
        ]
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
