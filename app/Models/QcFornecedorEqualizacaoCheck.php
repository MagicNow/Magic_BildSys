<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcFornecedorEqualizacaoCheck
 * @package App\Models
 * @version May 4, 2017, 10:37 am BRT
 */
class QcFornecedorEqualizacaoCheck extends Model
{
    public $table = 'qc_fornecedor_equalizacao_checks';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'qc_fornecedor_id',
        'user_id',
        'checkable_type',
        'checkable_id',
        'checked',
        'obs'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qc_fornecedor_id' => 'integer',
        'user_id' => 'integer',
        'checkable_type' => 'string',
        'checkable_id' => 'integer',
        'obs' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     **/
    public function checkable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function qcFornecedor()
    {
        return $this->belongsTo(\App\Models\QcFornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
