<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ContratoItemApropriacao
 * @package App\Models
 * @version May 18, 2017, 6:08 pm BRT
 */
class ContratoItemApropriacao extends Model
{
    public $table = 'contrato_item_apropriacoes';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contrato_item_id',
        'contrato_item_reapropriacao_id',
        'codigo_insumo',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id',
        'insumo_id',
        'qtd',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                      => 'integer',
        'contrato_item_id'        => 'integer',
        'codigo_insumo'           => 'string',
        'grupo_id'                => 'integer',
        'subgrupo1_id'            => 'integer',
        'subgrupo2_id'            => 'integer',
        'subgrupo3_id'            => 'integer',
        'servico_id'              => 'integer',
        'insumo_id'               => 'integer',
        'user_id'                 => 'integer',
        'qtd'                     => 'float'
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
    public function contratoItem()
    {
        return $this->belongsTo(ContratoItem::class);
    }

    public function codigoServico($showServico = true)
    {
       $grupos = [
            $this->grupo_id,
            $this->subgrupo1_id,
            $this->subgrupo2_id,
            $this->subgrupo3_id,
            $this->servico_id
        ];

       return implode('.', $grupos) . ($showServico ? (' ' . $this->servico->nome) : '');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ordemDeCompraItem()
    {
        return $this->belongsTo(OrdemDeCompraItem::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo1()
    {
        return $this->belongsTo(Grupo::class,'subgrupo1_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo2()
    {
        return $this->belongsTo(Grupo::class,'subgrupo2_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo3()
    {
        return $this->belongsTo(Grupo::class,'subgrupo3_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reapropriacoes()
    {
        return $this->hasMany(
            ContratoItemApropriacao::class,
            'contrato_item_reapropriacao_id'
        );
    }

    public function getQtdSobraAttribute()
    {
        return $this->qtd - $this->reapropriacoes->sum('qtd');
    }

    public function getQtdSobraFormattedAttribute()
    {
        return float_to_money($this->getQtdSobraAttribute(), '') . ' ' . $this->insumo->unidade_sigla;
    }

    public function getQtdFormattedAttribute()
    {
        return float_to_money($this->qtd, '') . ' ' . $this->insumo->unidade_sigla;
    }
}
