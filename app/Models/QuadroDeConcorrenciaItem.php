<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QuadroDeConcorrenciaItem
 * @package App\Models
 * @version May 3, 2017, 10:15 am BRT
 */
class QuadroDeConcorrenciaItem extends Model
{
    public $table = 'qc_itens';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'quadro_de_concorrencia_id',
        'qtd',
        'insumos_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'insumos_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public static $workflow_tipo_id = WorkflowTipo::QC;
    
    public function ordemDeCompraItens()
    {
        /**  @TODO Remover esta classe OcItem pois é um model repetido */
        return $this->belongsToMany(
            OcItem::class,
            'oc_item_qc_item',
            'qc_item_id',
            'ordem_de_compra_item_id'
        )
        ->withPivot(['id'])
        ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function logs()
    {
        return $this->hasMany(QcStatusLog::class, 'quadro_de_concorrencia_id');
    }

    public function qualObra()
    {
        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(\App\Models\QuadroDeConcorrencia::class);
    }

    public function ofertas()
    {
        return $this->hasMany(QcItemQcFornecedor::class, 'qc_item_id');
    }

    public function getObsAttribute()
    {
        return $this->ordemDeCompraItens->reduce(function($carry, $item) {
            $carry .= "Obra: " . $item->obra->nome;
            $carry .= "\nQuantidade: " . $item->qtd;
            $carry .= "\nObs: " . $item->obs;
            $carry .= "\n---\n";

            return $carry;
        }, "");
    }

    public function getTemsAttribute()
    {
        return $this->ordemDeCompraItens->reduce(function($carry, $item) {
            $carry .= $item->tems;
            $carry .= "\n---\n";

            return $carry;
        }, "");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratoItens()
    {
        return $this->hasMany(ContratoItem::class, 'qc_item_id');
    }

    public function dataUltimoPeriodoAprovacao(){
        $ultimoStatusAprovacao = $this->logs()->where('qc_status_id',QcStatus::EM_APROVACAO)
            ->orderBy('created_at','DESC')->first();
        if($ultimoStatusAprovacao){
            return $ultimoStatusAprovacao->created_at;
        }
        return null;
    }

}
