<?php

use Illuminate\Database\Seeder;

class LembreteTipoTableSeeder extends Seeder {

    public function run()
    {

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('lembrete_tipos')->delete();
        \Illuminate\Support\Facades\DB::table('lembrete_tipos')
            ->insert([
                [
                    'id'=> 1,
                    'nome'=> 'Start',
                    'dias_prazo_minimo' => 30
                ],
                [
                    'id'=> 2,
                    'nome'=> 'Negociação',
                    'dias_prazo_minimo' => 30
                ],
                [
                    'id'=> 3,
                    'nome'=> 'Mobilização',
                    'dias_prazo_minimo' => 30
                ]
            ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

    }

}
