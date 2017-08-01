<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Notafiscal
 * @package App\Models
 * @version June 28, 2017, 1:51 pm BRT
 */
class Notafiscal extends Model
{
    public $table = 'notas_fiscais';

    public $timestamps = false;

    public $fillable = [
        'contrato_id',
        'solicitacao_entrega_id',
        'xml',
        'codigo',
        'versao',
        'natureza_operacao',
        'data_emissao',
        'data_saida',
        'cnpj',
        'razao_social',
        'fantasia',
        'cnpj_destinatario',
        'arquivo_nfe'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_id' => 'integer',
        'solicitacao_entrega_id' => 'integer',
        'xml' => 'string',
        'codigo' => 'string',
        'versao' => 'string',
        'natureza_operacao' => 'string',
        'cnpj' => 'string',
        'razao_social' => 'string',
        'fantasia' => 'string',
        'cnpj_destinatario' => 'string',
        'arquivo_nfe' => 'string'
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
    public function solicitacaoEntrega()
    {
        return $this->belongsTo(\App\Models\SolicitacaoEntrega::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function notaFiscalItens()
    {
        return $this->hasMany(\App\Models\NotaFiscalIten::class);
    }
}
