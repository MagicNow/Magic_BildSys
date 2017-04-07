<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdemDeCompraItens
 * @package App\Models
 * @version April 7, 2017, 11:43 am BRT
 */
class OrdemDeCompraItens extends Model
{
    use SoftDeletes;

    public $table = 'ordem_de_compra_itens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'ordem_de_compra_id',
        'obra_id',
        'codigo_insumo',
        'qtd',
        'valor_unitario',
        'valor_total',
        'aprovado',
        'obs',
        'justificativa',
        'tems',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id',
        'insumo_id',
        'emergencial',
        'sugestao_data_uso',
        'sugestao_contrato_id',
        'user_id',
        'unidade_sigla'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ordem_de_compra_id' => 'integer',
        'obra_id' => 'integer',
        'codigo_insumo' => 'string',
        'obs' => 'string',
        'justificativa' => 'string',
        'tems' => 'string',
        'grupo_id' => 'integer',
        'subgrupo1_id' => 'integer',
        'subgrupo2_id' => 'integer',
        'subgrupo3_id' => 'integer',
        'servico_id' => 'integer',
        'insumo_id' => 'integer',
        'sugestao_data_uso' => 'date',
        'sugestao_contrato_id' => 'integer',
        'user_id' => 'integer',
        'unidade_sigla' => 'string'
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
    public function grupo()
    {
        return $this->belongsTo(\App\Models\Grupo::class);
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
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ordemDeCompra()
    {
        return $this->belongsTo(\App\Models\OrdemDeCompra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function servico()
    {
        return $this->belongsTo(\App\Models\Servico::class);
    }

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
    public function unidade()
    {
        return $this->belongsTo(\App\Models\Unidade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItemAnexos()
    {
        return $this->hasMany(\App\Models\OrdemDeCompraItemAnexo::class);
    }
}
