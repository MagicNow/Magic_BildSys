<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'aprovado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_id' => 'integer',
        'insumo_id' => 'integer',
        'qc_item_id' => 'integer'
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
        return $this->belongsTo(\App\Models\Insumo::class);
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
    public function contratoItemReapropriacos()
    {
        return $this->hasMany(\App\Models\ContratoItemReapropriaco::class);
    }

    public function applyChanges(ContratoItemModificacao $mod)
    {
        $this->aprovado       = true;
        $this->qtd            = $mod->qtd_atual;
        $this->valor_unitario = $mod->valor_unitario_atual;
        $this->valor_total    = (float) $this->qtd * (float) $this->valor_unitario;

        $this->save();

        return $this;
    }

}
