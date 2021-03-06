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
    const TIPOS_ENTRADA_SAIDA = [
        0 => 'Entrada',
        1 => 'Saída'
    ];

    const FRETE_POR_CONTA_DO = [
        0 => 'Emitente',
        1 => 'Destinatário',
    ];

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
        'arquivo_nfe',
        'nsu',
        'chave',
        'schema',

        'serie',
        'tipo_entrada_saida',
        'protocolo',
        'remetente_inscricao_estadual_sub',
        'remetente_endereco',
        'remetente_numero',
        'remetente_bairro',
        'remetente_cep',
        'remetente_cidade',
        'remetente_uf',
        'remetente_fone_fax',
        'destinatario_nome',
        'destinatario_endereco',
        'destinatario_numero',
        'destinatario_bairro',
        'destinatario_cep',
        'destinatario_cidade',
        'destinatario_cidade_codigo',
        'destinatario_uf',
        'destinatario_fone_fax',
        'destinatario_inscricao_estadual',
        'destinatario_inscricao_estadual_sub',
        'base_calculo_icms',
        'valor_icms',
        'base_calculo_icms_sub',
        'valor_icms_sub',
        'valor_imposto_importacao',
        'valor_icms_uf_remetente',
        'valor_fcp',
        'valor_pis',
        'valor_total_produtos',
        'valor_frete',
        'valor_seguro',
        'desconto',
        'outras_despesas',
        'valor_total_ipi',
        'valor_icms_uf_destinatario',
        'valor_total_tributos',
        'valor_confins',
        'valor_total_nota',
        'frete_por_conta',
        'transportadora_nome',
        'codigo_antt',
        'placa_veiculo',
        'veiculo_uf',
        'transportadora_cnpj',
        'transportadora_endereco',
        'transportadora_municipio',
        'transportadora_uf',
        'transportadora_inscricao',
        'transportadora_quantidade',
        'especie',
        'marca',
        'numeracao',
        'peso_bruto',
        'peso_liquido',
        'dados_adicionais',
        'manifesto',
        'manifesto_status',
        'retorno_manifesto_motivo',
        'status',
        'status_data',
        'status_user_id',
        'enviado_integracao',
        'integrado',
        'status_integracao',
        'codigo_integracao_mega',
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
        'arquivo_nfe' => 'string',
        'nsu' => 'integer',
        'manifesto_status' => 'integer',
        'manifesto' => 'integer',
        'chave' => 'string',
        'data_emissao' => 'datetime',
        'data_saida' => 'datetime',
        'retorno_manifesto_motivo' => 'string',
        'status'=> 'string',
        'status_data'=> 'datetime',
        'status_user_id'=> 'integer',
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
    public function itens()
    {
        return $this->hasMany(\App\Models\NotaFiscalItem::class, 'nota_fiscal_id');
    }

    public function pagamentos()
    {
        return $this->hasMany(\App\Models\Pagamento::class, 'notas_fiscal_id');
    }

    public function logs()
    {
        return $this->morphMany(LogIntegracao::class, 'loggable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function faturas()
    {
        return $this->hasMany(\App\Models\NotaFiscalFatura::class, 'nota_fiscal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function statusUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'status_user_id');
    }
}
