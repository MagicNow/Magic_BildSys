<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CatalogoContratoInsumo
 * @package App\Models
 * @version May 2, 2017, 6:02 pm BRT
 */
class CatalogoContratoInsumo extends Model
{

    public $table = 'catalogo_contrato_insumos';
    public $timestamps = true;

    public $fillable = [
        'catalogo_contrato_id',
        'insumo_id',
        'valor_unitario',
        'valor_maximo',
        'pedido_minimo',
        'pedido_multiplo_de',
        'qtd_minima',
        'periodo_inicio',
        'periodo_termino',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'catalogo_contrato_id' => 'integer',
        'insumo_id' => 'integer',
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

    public function getValorUnitarioAttribute($value)
    {
        return number_format($value,2,',','.');
    }

    public function getPedidoMinimoAttribute($value)
    {
        if(strlen($value) == 4){
            return '0'.number_format($value,2,',','.');
        }else{
            return number_format($value,2,',','.');
        }
    }

    public function setPedidoMinimoAttribute($value)
    {
        if($value){
            $this->attributes['pedido_minimo'] = money_to_float($value);
        }
    }

    public function getPedidoMultiploDeAttribute($value)
    {
        if(strlen($value) == 4){
            return '0'.number_format($value,2,',','.');
        }else{
            return number_format($value,2,',','.');
        }
    }

    public function setPedidoMultiploDeAttribute($value)
    {
        if($value) {
            $this->attributes['pedido_multiplo_de'] = money_to_float($value);
        }
    }
    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function catalogo()
    {
        return $this->belongsTo(CatalogoContrato::class, 'catalogo_contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
