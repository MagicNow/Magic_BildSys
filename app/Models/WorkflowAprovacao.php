<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkflowAprovacao
 * @package App\Models
 * @version April 11, 2017, 7:58 pm BRT
 */
class WorkflowAprovacao extends Model
{
    use SoftDeletes;

    public $table = 'workflow_aprovacoes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'workflow_alcada_id',
        'aprovavel_id',
        'aprovavel_type',
        'user_id',
        'aprovado',
        'workflow_reprovacao_motivo_id',
        'justificativa'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'workflow_alcada_id' => 'integer',
        'aprovavel_id' => 'integer',
        'aprovavel_type' => 'string',
        'user_id' => 'integer',
        'workflow_reprovacao_motivo_id' => 'integer',
        'aprovado' => 'integer',
        'justificativa' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'workflow_alcada_id' => 'required',
        'aprovavel_type' => 'required',
        'aprovavel_id' => 'required',
    ];


    public function aprovavel()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workflowAlcada()
    {
        return $this->belongsTo(WorkflowAlcada::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workflowReprovacaoMotivo()
    {
        return $this->belongsTo(WorkflowReprovacaoMotivo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function motivo()
    {
        return $this->belongsTo(WorkflowReprovacaoMotivo::class, 'workflow_reprovacao_motivo_id');
    }
}
