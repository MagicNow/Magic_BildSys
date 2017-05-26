<?php

namespace App\Repositories;

use App\Models\Contrato;
use InfyOm\Generator\Common\BaseRepository;

class ContratoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'contrato_status_id',
        'obra_id',
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'valor_total',
        'contrato_template_id',
        'arquivo',
        'campos_extras'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Contrato::class;
    }

    public function create(array $attributes)
    {
        // Busca o Fornecedor que vai ser gerado o contrato
        $qcFornecedor = $quadroDeConcorrencia
            ->qcFornecedores()
            ->where('qc_fornecedor.rodada',$quadroDeConcorrencia->rodada_atual)
            ->whereHas('itens', function($query){
                $query->where('vencedor','1');
            })
            ->with(['itens'=> function($query){
                $query->where('vencedor','1');
            }])
            ->where('qc_fornecedor.id',$attributes['qcFornecedor'])
            ->get();
        // Monta os itens do contrato

        // Salva o contrato

        // Salva os itens do contrato

        
        

        $model = parent::create($attributes);

        return $this->parserResult($model);
    }
}
