<?php

use Illuminate\Database\Seeder;

class ModulosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        DB::table('modulos')->delete();

        $items = [
            [
                'nome' => 'Orçamento inicial'
            ]
        ];

        foreach ($items as $item) {
            \App\Models\Modulo::create($item);
        }
    }
}
