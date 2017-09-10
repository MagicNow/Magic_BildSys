<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RetroalimentacaoObra
 * @package App\Models
 * @version April 28, 2017, 2:53 pm BRT
 */
class MascaraPadrao extends Model
{
    use SoftDeletes;

    public $table = 'mascara_padrao';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'insumo_id',           
        'coeficiente'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'insumo_id' => 'integer',
		'coeficiente' => 'float' 
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'insumo_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(\App\Models\Insumos::class);
    }

}
