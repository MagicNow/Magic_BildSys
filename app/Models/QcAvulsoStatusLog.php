<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QcAvulsoStatusLog
 * @package App\Models
 * @version October 10, 2017, 5:42 pm BRT
 *
 * @property \App\Models\Qc qc
 * @property \App\Models\QcStatus qcStatus
 * @property \App\Models\User user
 * @property \Illuminate\Database\Eloquent\Collection carteiraInsumos
 * @property \Illuminate\Database\Eloquent\Collection carteiraTipoEqualizacaoTecnicas
 * @property \Illuminate\Database\Eloquent\Collection carteiraUsers
 * @property \Illuminate\Database\Eloquent\Collection carteirasSla
 * @property \Illuminate\Database\Eloquent\Collection catalogoContratoInsumoLogs
 * @property \Illuminate\Database\Eloquent\Collection catalogoContratoObraLogs
 * @property \Illuminate\Database\Eloquent\Collection catalogoContratoRegionalLogs
 * @property \Illuminate\Database\Eloquent\Collection catalogoContratoStatusLogs
 * @property \Illuminate\Database\Eloquent\Collection compradorInsumos
 * @property \Illuminate\Database\Eloquent\Collection contratoInsumos
 * @property \Illuminate\Database\Eloquent\Collection contratoItemModificacaoApropriacao
 * @property \Illuminate\Database\Eloquent\Collection contratoItemModificacaoLog
 * @property \Illuminate\Database\Eloquent\Collection cronogramaFisicos
 * @property \Illuminate\Database\Eloquent\Collection estoque
 * @property \Illuminate\Database\Eloquent\Collection estoqueTransacao
 * @property \Illuminate\Database\Eloquent\Collection fornecedorServicos
 * @property \Illuminate\Database\Eloquent\Collection fornecedores
 * @property \Illuminate\Database\Eloquent\Collection fornecedoresAssociados
 * @property \Illuminate\Database\Eloquent\Collection insumoServico
 * @property \Illuminate\Database\Eloquent\Collection lembreteNotificaUsuarios
 * @property \Illuminate\Database\Eloquent\Collection lpuStatusLog
 * @property \Illuminate\Database\Eloquent\Collection mascaraPadraoInsumos
 * @property \Illuminate\Database\Eloquent\Collection medicaoBoletimMedicaoServico
 * @property \Illuminate\Database\Eloquent\Collection medicaoFisicaLogs
 * @property \Illuminate\Database\Eloquent\Collection medicoes
 * @property \Illuminate\Database\Eloquent\Collection memoriaCalculos
 * @property \Illuminate\Database\Eloquent\Collection nfSeItem
 * @property \Illuminate\Database\Eloquent\Collection obraUsers
 * @property \Illuminate\Database\Eloquent\Collection ocItemQcItem
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection permissionUser
 * @property \Illuminate\Database\Eloquent\Collection planejamentos
 * @property \Illuminate\Database\Eloquent\Collection qcFornecedorEqualizacaoChecks
 * @property \Illuminate\Database\Eloquent\Collection qcItens
 * @property \Illuminate\Database\Eloquent\Collection qcTipoEqualizacaoTecnica
 * @property \Illuminate\Database\Eloquent\Collection requisicaoItens
 * @property \Illuminate\Database\Eloquent\Collection requisicaoItensLog
 * @property \Illuminate\Database\Eloquent\Collection requisicaoLog
 * @property \Illuminate\Database\Eloquent\Collection roleUser
 * @property \Illuminate\Database\Eloquent\Collection seStatusLog
 * @property \Illuminate\Database\Eloquent\Collection workflowUsuarios
 * @property integer user_id
 * @property integer qc_status_id
 * @property integer qc_id
 */
class QcAvulsoStatusLog extends Model
{
    use SoftDeletes;

    public $table = 'qc_avulso_status_log';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'qc_status_id',
        'qc_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'qc_status_id' => 'integer',
        'qc_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function qc()
    {
        return $this->belongsTo(\App\Models\Qc::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function qcStatus()
    {
        return $this->belongsTo(\App\Models\QcStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
