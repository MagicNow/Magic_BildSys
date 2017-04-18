<?php

use Illuminate\Database\Seeder;

class TipoOrcamentoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        \Illuminate\Support\Facades\DB::table('orcamento_tipos')->delete();

        $items = [
            [
                'id' => 1,
                'nome' => 'Orçamento inicial'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\TipoOrcamento::create($item);
        }
    }
}
