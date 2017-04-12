<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdemDeCompraStatusLog
 * @package App\Models
 * @version April 7, 2017, 11:44 am BRT
 */
class OrdemDeCompraStatusLog extends Model
{
    use SoftDeletes;

    public $table = 'ordem_de_compra_status_log';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'oc_status_id',
        'ordem_de_compra_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'oc_status_id' => 'integer',
        'ordem_de_compra_id' => 'integer',
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
    public function ocStatus()
    {
        return $this->belongsTo(\App\Models\OcStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ordemDeCompra()
    {
        return $this->belongsTo(\App\Models\OrdemDeCompra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
