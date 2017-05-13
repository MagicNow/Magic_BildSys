<?php

namespace App\Repositories;

use App\Models\QcFornecedorEqualizacaoCheck;
use InfyOm\Generator\Common\BaseRepository;

class QcFornecedorEqualizacaoCheckRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'qc_fornecedor_id',
        'user_id',
        'checkable_type',
        'checkable_id',
        'checked',
        'obs'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QcFornecedorEqualizacaoCheck::class;
    }

    public function porQcFornecedor($qcFornecedor)
    {
        return $this->model
            ->where('qc_fornecedor_id', $qcFornecedor)
            ->get();
    }

}
