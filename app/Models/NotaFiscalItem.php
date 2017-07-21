<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotaFiscalItem
 * @package App\Models
 * @version July 20, 2017, 12:44 pm BRT
 */
class NotaFiscalItem extends Model
{
    public $table = 'nota_fiscal_itens';

    public $timestamps = false;

    public $fillable = [
        'nota_fiscal_id',
        'ncm',
        'codigo_produto',
        'nome_produto',
        'ean',
        'qtd',
        'valor_unitario',
        'valor_total',
        'unidade',
        'valor_tributavel',
        'icms',
        'ipi',
        'cofins'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nota_fiscal_id' => 'integer',
        'ncm' => 'integer',
        'codigo_produto' => 'string',
        'nome_produto' => 'string',
        'ean' => 'string',
        'unidade' => 'string'
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
    public function notaFiscal()
    {
        return $this->belongsTo(\App\Models\NotaFiscal::class, 'nota_fiscal_id');
    }
}
