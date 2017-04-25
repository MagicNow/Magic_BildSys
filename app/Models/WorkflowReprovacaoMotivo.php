<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkflowReprovacaoMotivo
 * @package App\Models
 * @version April 10, 2017, 12:27 pm BRT
 */
class WorkflowReprovacaoMotivo extends Model
{
    use SoftDeletes;

    public $table = 'workflow_reprovacao_motivos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'workflow_tipo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'workflow_tipo_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workflowAprovacos()
    {
        return $this->hasMany(\App\Models\WorkflowAprovaco::class);
    }

    public function tipo(){
        return $this->belongsTo(WorkflowTipo::class);
    }

    public function setWorkflowTipoIdAttribute($value){
        $this->attributes['workflow_tipo_id'] = intval($value) ? $value : null;
    }
}
