<?php

namespace App\Repositories;

use App\Models\Lpu;
use App\Models\Insumos;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LpuGerarRepository
{
    # Calcular as Lpu do Mês
    public static function calcular()
    {
        $insumos = Contrato::select([
            
			'contrato_itens.id',
			'insumos.codigo',
			'insumos.nome as insumo',
			'insumo_grupos.nome as grupo',
			'insumos.unidade_sigla',
			'contrato_itens.valor_unitario',
			'contrato_itens.updated_at',
			'fornecedores.nome as fornecedor',
			'regionais.id as regional_id',
			'regionais.nome as regional'			
		
		])
        ->from('contratos')        
        ->join('obras', 'obras.id', 'contratos.obra_id')
		->join('regionais', 'regionais.id', 'obras.regional_id')
		->join('fornecedores', 'fornecedores.id', 'contratos.fornecedor_id')
		->join('contrato_status', 'contrato_status.id', 'contratos.contrato_status_id')
		->join('contrato_itens', 'contrato_itens.contrato_id', 'contratos.id')
		->join('insumos', 'insumos.id', 'contrato_itens.insumo_id')
		->join('insumo_grupos', 'insumo_grupos.id', 'insumos.insumo_grupo_id')
        ->where('contrato_status.id','5')        
        ->get();

        foreach ($insumos as $insumo) {
            try {
                
                $lpu = Lpu::updateOrCreate([                
                    'insumo_id' => $insumo->insumo_id,
					'valor_sugerido'	=> $insumo->valor_unitario
                ]);
                               
				$lpu->codigo_insumo = $insumo->codigo;
				$lpu->regional_id = $insumo->regional_id;
				$lpu->grupo_id = "1";
				$lpu->subgrupo1_id = "1";
				$lpu->subgrupo2_id = "1";
				$lpu->subgrupo3_id = "1";
				$lpu->servico_id = "1";
				$lpu->valor_contrato = "1.00";
				$lpu->valor_catalogo = "1.00";

                $lpu->save();
            } catch (\Exception $e) {
                Log::error('Erro ao importar lpu '. $insumo->insumo_id. ': '.$e->getMessage());
            }
        }

        return ['total-lpu' => $insumos->count(), 'total-sys' => Lpu::count()];
    }

}
