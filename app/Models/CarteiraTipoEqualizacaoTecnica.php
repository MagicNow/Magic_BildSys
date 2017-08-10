<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CarteiraTipoEqualizacaoTecnica
 * @package App\Models
 * @version April 25, 2017, 6:11 pm BRT
 */
class CarteiraTipoEqualizacaoTecnica extends Model
{
    use SoftDeletes;

    public $table = 'carteira_tipo_equalizacao_tecnicas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
		'carteira_id',
        'tipo_equalizacao_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carteira_id' => 'integer',
        'tipo_equalizacao_id' => 'integer'		
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
    public function carteira()
    {
        return $this->belongsTo(\App\Models\Carteira::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipo_equalizacao_tecnica()
    {
        return $this->belongsTo(\App\Models\TipoEqualizacaoTecnica::class);
    }
}
