<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CatalogoContratoInsumoLog
 * @package App\Models
 * @version June 15, 2017, 6:12 pm BRT
 */
class CatalogoContratoInsumoLog extends Model
{
    use SoftDeletes;

    public $table = 'catalogo_contrato_insumo_logs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'contrato_insumo_id',
        'user_id',
        'valor_unitario_anterior',
        'pedido_minimo_anterior',
        'pedido_multiplo_de_anterior',
        'periodo_inicio_anterior',
        'periodo_termino_anterior',
        'valor_unitario',
        'pedido_minimo',
        'pedido_multiplo_de',
        'periodo_inicio',
        'periodo_termino'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_insumo_id' => 'integer',
        'user_id' => 'integer',
        'periodo_inicio_anterior' => 'date',
        'periodo_termino_anterior' => 'date',
        'periodo_inicio' => 'date',
        'periodo_termino' => 'date'
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
    public function catalogoContratoInsumo()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoInsumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
