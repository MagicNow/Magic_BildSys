<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Contratos
 * @package App\Models
 * @version April 12, 2017, 9:34 am BRT
 */
class Contrato extends Model
{

    public $table = 'contratos';
    public $timestamps = false;
    
    public $fillable = [
        'obra_id',
        'data',
        'valor',
        'fornecedor_cod',
        'fornecedor_nome',
        'arquivo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'fornecedor_cod' => 'integer',
        'fornecedor_nome' => 'string',
        'arquivo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function getValorAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function setValorAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['valor'] = $result;
    }

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
    public function contratoInsumos()
    {
        return $this->hasMany(\App\Models\ContratoInsumo::class, 'contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(\App\Models\OrdemDeCompraItens::class);
    }
}
