<?php

namespace App\Repositories;

use App\Models\Lpu;
use App\Models\Insumos;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LpuGerarRepository
{
	#Calcular por catalago/contrato
	#Calcular por QC para insumo quebrado
	#AVG e medias do subgrupo1_id
	#Retornar subgrupo ou insumo?
	
    # Calcular as Lpu do Mês
    public static function calcular()
    {
        $insumos = Lpu::select([
            
			'contrato_itens.id',
			'insumos.id as insumo_id',
			'insumos.codigo as codigo_insumo',
			'insumos.nome as insumo',
			'insumo_grupos.nome as grupo',
			'insumos.unidade_sigla',	
			DB::raw('AVG(contrato_itens.valor_unitario) as valor_sugerido'),
			'contrato_itens.valor_unitario as valor_contrato',
			DB::raw('exists (select catalogo_contrato_insumos.valor_unitario from catalogo_contrato_insumos where catalogo_contrato_insumos.insumo_id = insumos.id) as valor_catalogo'),
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
		->groupBy('insumos.id')      
        ->get();

        foreach ($insumos as $insumo) {
            try {
				
                $lpu = Lpu::updateOrCreate([                
                    'insumo_id' => $insumo->insumo_id,
					'codigo_insumo'   => $insumo->codigo_insumo,
					'regional_id'   => $insumo->regional_id,					
					'valor_sugerido'   => $insumo->valor_sugerido,
					'valor_contrato'   => $insumo->valor_contrato,
					'valor_catalogo'   => $insumo->valor_catalogo,									
                ]);	  

                $lpu->valor_sugerido = $insumo->valor_sugerido;
                $lpu->valor_contrato  =  $insumo->valor_contrato;
                $lpu->valor_catalogo  =  $insumo->valor_catalogo;

                $lpu->save();
				
            } catch (\Exception $e) {
                Log::error('Erro ao importar lpu '. $insumo->insumo_id. ': '.$e->getMessage());
            }
        }

        return ['total-lpu' => $insumos->count(), 'total-sys' => Lpu::count()];
    }

}
