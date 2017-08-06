<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CarteiraInsumo
 * @package App\Models
 * @version May 11, 2017, 8:46 pm BRT
 */
class CarteiraInsumo extends Model
{
    public $table = 'carteira_insumos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'carteira_id',
        'insumo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'carteira_id' => 'integer',
        'insumo_id' => 'integer'
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
    public function insumo()
    {
        return $this->belongsTo(\App\Models\Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function carteira()
    {
        return $this->belongsTo(\App\Models\Carteira::class);
    }
		
}
