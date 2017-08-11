<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdemDeCompraItemLog
 * @package App\Models
 * @version August 11, 2017, 2:35 pm -03
 */
class OrdemDeCompraItemLog extends Model
{
    use SoftDeletes;

    public $table = 'ordem_de_compra_item_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'oc_status_id',
        'ordem_de_compra_item_id',
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
        'ordem_de_compra_item_id' => 'integer',
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
        return $this->belongsTo(OcStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ordemDeCompraIten()
    {
        return $this->belongsTo(OrdemDeCompraItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
