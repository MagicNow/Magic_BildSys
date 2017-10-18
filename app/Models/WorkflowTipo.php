<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class WorkflowTipo
 * @package App\Models
 * @version April 10, 2017, 1:04 pm BRT
 */
class WorkflowTipo extends Model
{
    public $table = 'workflow_tipos';

    public $timestamps = false;

    const OC = 1;
    const QC = 2;
    const CONTRATO = 3;
    const ITEM_CONTRATO = 4;
    const SOLICITACAO_ENTREGA = 5;
    const MEDICAO = 6;
    const QC_AVULSO = 7;

    public static function qualTipo($id){
        switch ($id){
            case 1:
                return 'OrdemDeCompraItem';
                break;
            case 2:
                return 'QuadroDeConcorrencia';
                break;
            case 3:
                return 'Contrato';
                break;
            case 4:
                return 'ContratoItemModificacao';
                break;
            case 5:
                return 'SolicitacaoEntrega';
                break;
            case 6:
                return 'Medicao';
                break;
            case 7:
                return 'Qc';
                break;
        }
    }

    public $fillable = [
        'nome',
        'dias_prazo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'dias_prazo' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workflowAlcadas()
    {
        return $this->hasMany(\App\Models\WorkflowAlcada::class);
    }
}
