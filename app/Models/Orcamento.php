<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Orcamento
 * @package App\Models
 * @version April 4, 2017, 5:37 pm BRT
 */
class Orcamento extends Model
{
    public $table = 'orcamentos';

    public $timestamps = false;

    public $fillable = [
        'obra_id',
        'codigo_insumo',
        'insumo_id',
        'servico_id',
        'grupo_id',
        'unidade_sigla',
        'coeficiente',
        'indireto',
        'terreo_externo_solo',
        'terreo_externo_estrutura',
        'terrreo_interno',
        'primeiro_pavimento',
        'segundo_ao_penultimo',
        'cobertura_ultimo_piso',
        'atico',
        'reservatorio',
        'qtd_total',
        'preco_unitario',
        'preco_total',
        'referencia_preco',
        'obs',
        'porcentagem_orcamento',
        'orcamento_tipo_id',
        'ativo',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'user_id',
        'descricao'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'codigo_insumo' => 'string',
        'insumo_id' => 'integer',
        'servico_id' => 'integer',
        'grupo_id' => 'integer',
        'unidade_sigla' => 'string',
        'referencia_preco' => 'string',
        'obs' => 'text',
        'orcamento_tipo_id' => 'integer',
        'subgrupo1_id' => 'integer',
        'subgrupo2_id' => 'integer',
        'subgrupo3_id' => 'integer',
        'descricao' => 'string',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    public static $relation = [
        'codigo_insumo' => 'string',
        'descricao' => 'string',
        'unidade_sigla' => 'string',
        'coeficiente' => 'decimal',
        'indireto' => 'decimal',
        'terreo_externo_solo' => 'decimal',
        'terreo_externo_estrutura' => 'decimal',
        'terreo_interno' => 'decimal',
        'primeiro_pavimento' => 'decimal',
        'segundo_ao_penultimo' => 'decimal',
        'cobertura_ultimo_piso' => 'decimal',
        'atico' => 'decimal',
        'reservatorio' => 'decimal',
        'qtd_total' => 'decimal',
        'preco_unitario' => 'decimal',
        'preco_total' => 'decimal',
        'referencia_preco' => 'string',
        'obs' => 'text',
        'porcentagem_orcamento' => 'decimal'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'unidade_sigla' => 'required',
        'codigo_insumo' => 'required'
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
    public function orcamentoTipo()
    {
        return $this->belongsTo(\App\Models\TipoOrcamento::class);
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
    public function grupo1()
    {
        return $this->belongsTo(Grupo::class,'subgrupo1_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo2()
    {
        return $this->belongsTo(Grupo::class,'subgrupo2_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo3()
    {
        return $this->belongsTo(Grupo::class,'subgrupo3_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unidade()
    {
        return $this->belongsTo(\App\Models\Unidade::class);
    }
}
