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
        DB::table('orcamento_tipos')->delete();

        $items = [
            [
                'nome' => 'Orçamento inicial'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\TipoOrcamento::create($item);
        }
    }
}
