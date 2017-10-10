<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisicaoStatus extends Model
{
    use SoftDeletes;

    public $table = 'requisicao_status';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    const NOVA              = 1;
    const EM_SEPARACAO      = 2;
    const EM_TRANSITO       = 3;
    const APLICADO_PARCIAL  = 4;
    const APLICADO_TOTAL    = 5;
    const CANCELADA         = 6;

    public $fillable = [
        'nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

}
