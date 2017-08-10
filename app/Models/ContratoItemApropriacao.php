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
        'user_id',
        'descricao'
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
        return $this->belongsTo(ContratoItem::class, 'contrato_item_id');
    }

    public function codigoEstruturado()
    {
       $grupos = [
            $this->servico->codigo,
            $this->insumo->codigo
        ];

       return implode('.', $grupos);
    }

    public function codigoServico($showServico = true)
    {
       return $this->servico->codigo . ($showServico ? (' ' . $this->servico->nome) : '');
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
        return $this->belongsTo(Servico::class, 'servico_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo()
    {
        return $this->belongsTo(Grupo::class,'grupo_id');
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

    public function ligacao()
    {
        return $this->hasOne(
            ApropriacaoLigacao::class,
            'contrato_item_apropriacao_id'
        );
    }

    public function modificacoes()
    {
        return $this->belongsToMany(
            ContratoItemModificacao::class,
            'contrato_item_modificacao_apropriacao',
            'contrato_item_apropriacao_id',
            'contrato_item_modificacao_id'
        )
        ->withPivot([ 'qtd_atual', 'qtd_anterior', 'id', 'descricao' ])
        ->withTimestamps();
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

    public function seApropriacoes()
    {
        return $this->hasMany(SeApropriacao::class, 'contrato_item_apropriacao_id');
    }

    public function getQtdSaldoAttribute()
    {
        $se_apropriacoes = $this->seApropriacoes()
            ->whereHas('solicitacaoEntregaItem', function($query) {
                $query->whereHas('solicitacaoEntrega', function($query) {
                    $query->where('se_status_id', '!=', SeStatus::CANCELADO);
                });
            })
            ->get();

        if($this->insumo->is_faturamento_direto) {
            $total_solicitado = $se_apropriacoes->reduce(function($sum, $se_a) {
                $sum += $se_a->solicitacaoEntregaItem->valor_unitario * $se_a->qtd;

                return $sum;
            }, 0);
        } else {
            $total_solicitado = $se_apropriacoes->sum('qtd');
        }

        return $this->qtd - $total_solicitado;
    }
}
