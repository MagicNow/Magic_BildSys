<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class QcItemQcFornecedor
 * @package App\Models
 * @version May 4, 2017, 11:14 am BRT
 */
class QcItemQcFornecedor extends Model
{
    use SoftDeletes;

    public $table = 'qc_item_qc_fornecedor';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qc_item_id' => 'integer',
        'qc_fornecedor_id' => 'integer',
        'user_id' => 'integer'
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
    public function qcFornecedor()
    {
        return $this->belongsTo(\App\Models\QcFornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function qcIten()
    {
        return $this->belongsTo(\App\Models\QcIten::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
