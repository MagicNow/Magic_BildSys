<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class InsumoServico
 * @package App\Models
 * @version April 20, 2017, 6:21 pm BRT
 */
class InsumoServico extends Model
{
    public $table = 'insumo_servico';
    
    public $timestamps = false;

    public $fillable = [
        'servico_id',
        'insumo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'servico_id' => 'integer',
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
    public function servico()
    {
        return $this->belongsTo(\App\Models\Servico::class);
    }
}
