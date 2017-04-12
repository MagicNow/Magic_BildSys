<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdemDeCompraItemAnexo
 * @package App\Models
 * @version April 11, 2017, 7:24 pm BRT
 */
class OrdemDeCompraItemAnexo extends Model
{
    use SoftDeletes;

    public $table = 'ordem_de_compra_item_anexos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'ordem_de_compra_item_id',
        'arquivo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ordem_de_compra_item_id' => 'integer',
        'arquivo' => 'string'
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
    public function ordemDeCompraIten()
    {
        return $this->belongsTo(\App\Models\OrdemDeCompraIten::class);
    }
}
