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
