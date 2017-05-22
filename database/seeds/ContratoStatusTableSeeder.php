<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\ContratoStatus;

class ContratoStatusTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('contrato_status')->truncate();

        DB::table('contrato_status')->insert([
            'id' => ContratoStatus::EM_APROVACAO,
            'nome' => 'Em Aprovação',
            'cor' => '#DEA447'
        ], [
            'id' => ContratoStatus::APROVADO,
            'nome' => 'Aprovado',
            'cor' => '#2BBD30'
        ], [
            'id' => ContratoStatus::REPROVADO,
            'nome' => 'Reprovado',
            'cor' => '#CC2910'
        ], [
            'id' => ContratoStatus::AGUARDANDO,
            'nome' => 'Aguardando Liberação',
            'cor' => '#CCCCCC'
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
