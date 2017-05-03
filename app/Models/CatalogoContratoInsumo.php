<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContratoInsumo
 * @package App\Models\Admin
 * @version May 2, 2017, 6:02 pm BRT
 */
class CatalogoContratoInsumo extends Model
{

    public $table = 'catalogo_contrato_insumos';
    public $timestamps = false;

    public $fillable = [
        'catalogo_contrato_id',
        'insumo_id',
        'valor_unitario',
        'valor_maximo',
        'pedido_minimo',
        'pedido_multiplo_de'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'catalogo_contrato_id' => 'integer',
        'insumo_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function getValorUnitarioAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function setValorUnitarioAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['valor_unitario'] = $result;
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

    public function getPedidoMinimoAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setPedidoMinimoAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['pedido_minimo'] = $result;
    }

    public function getPedidoMultiploDeAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setPedidoMultiploDeAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['pedido_multiplo_de'] = $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contrato()
    {
        return $this->belongsTo(\App\Models\CatalogoContratoInsumo::class, 'catalogo_contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(\App\Models\Insumo::class);
    }
}
