<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcItem
 * @package App\Models
 * @version May 3, 2017, 3:17 pm BRT
 */
class QcItem extends Model
{
    public $table = 'qc_itens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'quadro_de_concorrencia_id',
        'qtd',
        'insumo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
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
        return $this->belongsTo(Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(QuadroDeConcorrencia::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function oc_itens()
    {
        return $this->belongsToMany(OrdemDeCompraItem::class,'oc_item_qc_item','qc_item_id','ordem_de_compra_item_id')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function propostaFornecedores()
    {
        return $this->hasMany(QcItemQcFornecedor::class);
    }
}
