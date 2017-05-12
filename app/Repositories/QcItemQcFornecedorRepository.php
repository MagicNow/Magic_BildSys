<?php

namespace App\Repositories;

use App\Models\QcItemQcFornecedor;
use InfyOm\Generator\Common\BaseRepository;

class QcItemQcFornecedorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'qc_item_id',
        'qc_fornecedor_id',
        'user_id',
        'qtd',
        'valor_unitario',
        'valor_total',
        'vencedor',
        'data_decisao'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcItemQcFornecedor::class;
    }

    public function findByQcItemQcFornecedor($qcItem, $qcFornecedor)
    {
        return $this->model
            ->where('qc_item_id', $qcItem)
            ->where('qc_fornecedor_id', $qcFornecedor)
            ->firstOrFail();
    }
}
