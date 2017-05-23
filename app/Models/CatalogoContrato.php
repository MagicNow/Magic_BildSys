<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContrato
 * @package App\Models
 * @version May 2, 2017, 6:01 pm BRT
 */
class CatalogoContrato extends Model
{

    public $table = 'catalogo_contratos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'fornecedor_id',
        'data',
        'valor',
        'arquivo',
        'periodo_inicio',
        'periodo_termino',
        'valor_minimo',
        'valor_maximo',
        'qtd_minima',
        'qtd_maxima',
        'fornecedor_cod',
        'fornecedor_nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'fornecedor_id' => 'integer',
        'data' => 'date',
        'arquivo' => 'string',
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

    public function getValorMinimoAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function setValorMinimoAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['valor_minimo'] = $result;
    }

    public function getValorMaximoAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function setValorMaximoAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['valor_maximo'] = $result;
    }

    public function getQtdMinimaAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setQtdMinimaAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['qtd_minima'] = $result;
    }

    public function getQtdMaximaAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setQtdMaximaAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['qtd_maxima'] = $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedor()
    {
        return $this->belongsTo(\App\Models\Fornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoInsumos()
    {
        return $this->hasMany(\App\Models\CatalogoContratoInsumo::class, 'catalogo_contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(\App\Models\OrdemDeCompraItens::class);
    }
}
