<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\SeStatus;

/**
 * Class ContratoItem
 * @package App\Models
 * @version May 18, 2017, 6:08 pm BRT
 */
class ContratoItem extends Model
{
    public $table = 'contrato_itens';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contrato_id',
        'insumo_id',
        'qc_item_id',
        'qtd',
        'valor_unitario',
        'valor_total',
        'aprovado',
        'pendente',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'contrato_id'    => 'integer',
        'insumo_id'      => 'integer',
        'qc_item_id'     => 'integer',
        'pendente'       => 'boolean',
        'aprovado'       => 'boolean',
        'valor_unitario' => 'float',
        'valor_total'    => 'float',
        'qtd'            => 'float',
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
    public function contrato()
    {
        return $this->belongsTo(\App\Models\Contrato::class);
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
    public function qcItem()
    {
        return $this->belongsTo(QuadroDeConcorrenciaItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function modificacoes()
    {
        return $this->hasMany(ContratoItemModificacao::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoItemReapropriacao()
    {
        return $this->hasMany(ContratoItemApropriacao::class);
    }

    public function applyChanges(ContratoItemModificacao $mod, $tipo_reajuste = null)
    {
        $this->aprovado = true;
        
        if($tipo_reajuste){
            if($tipo_reajuste == ContratoItemModificacao::REAJUSTE_QTD){
                $this->qtd = $mod->qtd_atual;
            }
            if($tipo_reajuste == ContratoItemModificacao::REAJUSTE_VALOR){
                $this->valor_unitario = $mod->valor_unitario_atual;
            }
        } else {
            $this->qtd = $mod->qtd_atual;
            $this->valor_unitario = $mod->valor_unitario_atual;
        }
        
        $this->valor_total = (float) $this->qtd * (float) $this->valor_unitario;

        $this->save();

        return $this;
    }

    public function apropriacoes()
    {
        return $this->hasMany(
            ContratoItemApropriacao::class,
            'contrato_item_id'
        );
    }

    public function solicitacaoEntregaItens()
    {
        return $this->hasMany(
            SolicitacaoEntregaItem::class,
            'contrato_item_id'
        );
    }

    public function getQtdSaldoAttribute()
    {
        $columnToSum = $this->insumo->is_faturamento_direto
            ? 'valor_total'
            : 'qtd';


        $total_solicitado = $this->solicitacaoEntregaItens()
            ->whereHas('solicitacaoEntrega', function($query) {
                $query->where('se_status_id', '!=', SeStatus::CANCELADO);
            })
            ->sum($columnToSum);

        return $this->qtd - $total_solicitado;
    }
}
