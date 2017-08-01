<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ObraTorre
 * @package App\Models
 * @version June 28, 2017, 6:59 pm BRT
 */
class ObraTorre extends Model
{

    public $table = 'obra_torres';
    public $timestamps = false;
    
    public $fillable = [
        'obra_id',
        'nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'nome' => 'string'
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
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mcMedicaoPrevisos()
    {
        return $this->hasMany(\App\Models\McMedicaoPreviso::class);
    }
}
