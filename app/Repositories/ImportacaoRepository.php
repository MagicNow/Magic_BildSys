<?php

namespace App\Repositories;

use App\Models\Insumo;
use App\Models\InsumoGrupo;
use App\Models\MegaInsumo;
use App\Models\MegaInsumoGrupo;
use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportacaoRepository
{
    public static function insumos()
    {
        $insumos = MegaInsumo::select([
            'PRO_IN_CODIGO',
            'PRO_ST_DESCRICAO',
            'GRU_IN_CODIGO',
            'UNI_ST_UNIDADE',
        ])
            ->where('gru_ide_st_codigo','07')
            ->whereRaw(DB::raw(" NOT EXISTS (
                                            SELECT 1 
                                              FROM MGADM.EST_INATIVAPRODUTO ipr
                                              WHERE ipr.PRO_TAB_IN_CODIGO = MGADM.EST_PRODUTOS.PRO_TAB_IN_CODIGO
                                              AND ipr.PRO_PAD_IN_CODIGO = MGADM.EST_PRODUTOS.PRO_PAD_IN_CODIGO
                                              AND ipr.PRO_IN_CODIGO = MGADM.EST_PRODUTOS.PRO_IN_CODIGO)")
                        )
            ->get();
        foreach ($insumos as $produto) {
            try {
                $unidade = Unidade::where('sigla', trim(utf8_encode($produto->uni_st_unidade)))->first();
                if (!$unidade) {
                    $unidade = Unidade::create([
                        'sigla' => trim(utf8_encode($produto->uni_st_unidade)),
                        'descricao' => trim(utf8_encode($produto->uni_st_unidade)),
                    ]);
                }

                $insumo = Insumo::firstOrCreate([
                    'nome' => trim(utf8_encode($produto->pro_st_descricao)),
                    'unidade_sigla' => trim(utf8_encode($produto->uni_st_unidade)),
                    'codigo' => $produto->pro_in_codigo,
                    'insumo_grupo_id' => $produto->gru_in_codigo
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao importar insumo '. $produto->pro_in_codigo. ': '.$e->getMessage());
            }
        }

        return ['total-mega' => $insumos->count(), 'total-sys' => Insumo::count()];
    }

    public static function insumo_grupos()
    {
        $grupos_mega = MegaInsumoGrupo::select([
            'GRU_IDE_ST_CODIGO',
            'GRU_IN_CODIGO',
            'GRU_ST_NOME',])
            ->where('gru_ide_st_codigo' , '07')
            ->get();
        foreach ($grupos_mega as $grupo) {
            try {
                $insumoGrupo = InsumoGrupo::firstOrCreate([
                    'id' => $grupo->gru_in_codigo,
                    'codigo_identificador' => $grupo->gru_ide_st_codigo,
                    'nome' => trim(utf8_encode($grupo->gru_st_nome))
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao importar grupo de insumo '.$grupo->gru_in_codigo.': '.$e->getMessage());
            }
        }
        return ['total-mega' => $grupos_mega->count(), 'total-sys' => InsumoGrupo::count()];
    }
}
