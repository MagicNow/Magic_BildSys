<?php

use Illuminate\Database\Seeder;

class DesistenciaMotivosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('desistencia_motivos')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Sem interesse'
            ],
            [
                'id' => 2,
                'nome' => 'Sem mão de obra'
            ],
            [
                'id' => 3,
                'nome' => 'Outro'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\DesistenciaMotivo::create($item);
        }
    }
}
