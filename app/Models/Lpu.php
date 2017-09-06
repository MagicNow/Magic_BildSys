<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Lpu
 * @package App\Models
 * @version May 18, 2017, 6:08 pm BRT
 */
class Lpu extends Model
{
    public $table = 'lpu';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'insumo_id',
		'codigo_insumo',
		'regional_id',
		'grupo_id',
		'subgrupo1_id',
		'subgrupo2_id',
		'subgrupo3_id',
		'servico_id',
		'valor_sugerido',
		'valor_contrato',
		'valor_catalogo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'             => 'integer',
        'insumo_id'      => 'integer',
        'valor_sugerido' => 'float',
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
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function modificacoes()
    {
        return $this->hasMany(LpuModificacao::class);
    }

    /**
     * applyChanges
     * Aplica as mudanças no item da Lpu
     * @param LpuModificacao $mod
     * @param 'Reajuste de valor unitário'
     * @return Lpu $this
     */
    public function applyChanges(LpuModificacao $mod, $tipo_reajuste = null)
    {
        
        if($tipo_reajuste){            
            if($tipo_reajuste == ContratoItemModificacao::REAJUSTE_VALOR){
                $this->valor_unitario = $mod->valor_unitario_atual;
            }
        } 
        
        $this->save();

        return $this;
    }

    /*public function getQtdSaldoAttribute()
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
    }*/
}
