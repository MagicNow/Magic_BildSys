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
        'cfop',
        'nome_produto',
        'codigo_produto',
        'ean',
        'qtd',
        'valor_unitario',
        'valor_total',
        'unidade',
        'base_calculo_icms',
        'base_calculo_pis',
        'base_calculo_cofins',
        'aliquota_icms',
        'valor_icms',
        'valor_ipi',
        'aliquota_ipi',
        'aliquota_cofins',
        'valor_cofins',
        'aliquota_pis',
        'valor_pis',
        'solicitacao_entrega_itens_id',
        'base_calculo_icms_uf_dest',
        'aliquota_fcp_icms_uf_dest',
        'aliquota_icms_uf_dest',
        'aliquota_icms_uf_interna',
        'aliquota_icms_uf_interna_part',
        'valor_fcp_icms_uf_dest',
        'valor_icms_uf_dest',
        'valor_icms_uf_remetente',
        'situacao_tributacao_ipi',
        'situacao_tributacao_pis',
        'situacao_tributacao_cofins',
        'codigo_enquadramento_ipi',
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
        'unidade' => 'string',
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function solicitacaoEntregaItens()
    {
        return $this->belongsToMany(\App\Models\SolicitacaoEntregaItem::class,
            'nf_se_item',
            'nota_fiscal_item_id',
            'solicitacao_entrega_item_id'
        );
    }
}
