<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class WorkflowAlcada
 * @package App\Models
 * @version April 10, 2017, 12:57 pm BRT
 */
class WorkflowAlcada extends Model
{
    use SoftDeletes;

    public $table = 'workflow_alcadas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'workflow_tipo_id',
        'nome',
        'ordem',
        'dias_prazo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'workflow_tipo_id' => 'integer',
        'nome' => 'string',
        'ordem' => 'integer',
        'dias_prazo' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'workflow_tipo_id' => 'required',
        'nome' => 'required',
//        'workflowUsuarios' => 'required|min:1'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function workflowTipo()
    {
        return $this->belongsTo(WorkflowTipo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workflowAprovacoes()
    {
        return $this->hasMany(WorkflowAprovacoes::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function workflowUsuarios()
    {
        return $this->belongsToMany(User::class,'workflow_usuarios','workflow_alcada_id','user_id')->withPivot('deleted_at')->withTimestamps();
    }
}
