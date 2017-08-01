<?php

namespace App\Repositories;

use App\Models\QcFornecedor;
use InfyOm\Generator\Common\BaseRepository;

class QcFornecedorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'user_id',
        'rodada',
        'porcentagem_material',
        'porcentagem_servico',
        'porcentagem_faturamento_direto',
        'desistencia_motivo_id',
        'desistencia_texto'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcFornecedor::class;
    }

    public function buscarPorQuadroEFornecedor($quadro_id, $fornecedor_id)
    {
        return $this->model
            ->select('qc_fornecedor.*')
            ->join(
                'quadro_de_concorrencias',
                'quadro_de_concorrencias.id',
                'qc_fornecedor.quadro_de_concorrencia_id'
            )
            ->where('fornecedor_id', $fornecedor_id)
            ->where('quadro_de_concorrencia_id', $quadro_id)
            ->whereNull('desistencia_motivo_id')
            ->whereNull('desistencia_texto')
            ->whereColumn('quadro_de_concorrencias.rodada_atual', 'qc_fornecedor.rodada')
            ->first();
    }

    public function buscarPorQuadroNaRodada($quadro_id, $rodada = null)
    {
        return $this->model
            ->select('qc_fornecedor.*')
            ->join(
                'quadro_de_concorrencias',
                'quadro_de_concorrencias.id',
                'qc_fornecedor.quadro_de_concorrencia_id'
            )
            ->where('quadro_de_concorrencia_id', $quadro_id)
            ->whereNull('desistencia_motivo_id')
            ->whereNull('desistencia_texto')
            ->where(function($query) use ($rodada) {
                if(!is_null($rodada)) {
                    $query->where('qc_fornecedor.rodada', $rodada);

                    return $query;
                }

                $query->whereColumn(
                    'quadro_de_concorrencias.rodada_atual',
                    'qc_fornecedor.rodada'
                );
            })
            ->get();
    }

    public function queOfertaramNoQuadroNaRodada($quadro_id, $rodada = null)
    {
        return $this->model
            ->select('qc_fornecedor.*')
            ->join(
                'quadro_de_concorrencias',
                'quadro_de_concorrencias.id',
                'qc_fornecedor.quadro_de_concorrencia_id'
            )
            ->where('quadro_de_concorrencia_id', $quadro_id)
//            ->whereNull('desistencia_motivo_id')
//            ->whereNull('desistencia_texto')
            ->where(function($query) use ($rodada) {
                if(!is_null($rodada)) {
                    $query->where('qc_fornecedor.rodada', $rodada);

                    return $query;
                }

                $query->whereColumn(
                    'quadro_de_concorrencias.rodada_atual',
                    'qc_fornecedor.rodada'
                );
            })
            ->where(function($query) {
                $query->where(function($query) {
                   $query->whereNull('desistencia_motivo_id')
                       ->whereNull('desistencia_texto')
                       ->has('itens');
                });
                $query->orWhere(function($query) {
                    $query->whereNotNull('desistencia_motivo_id')
                        ->whereNotNull('desistencia_texto');
                });
            })
            ->get();
    }
}
