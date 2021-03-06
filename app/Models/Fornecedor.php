<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpDocumentor\Reflection\Types\Array_;

/**
 * Class Fornecedor
 * @package App\Models
 * @version May 4, 2017, 12:21 pm BRT
 */
class Fornecedor extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    public static $campos = [
        'codigo_mega',
        'nome',
        'cnpj',
        'tipo_logradouro',
        'logradouro',
        'numero',
        'complemento',
        'cidade_id',
        'municipio',
        'estado',
        'situacao_cnpj',
        'inscricao_estadual',
        'email',
        'site',
        'telefone',
        'cep',
        'user_id'
    ];
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
        'cnpj' => 'required',
        'email' => 'required|email|unique:fornecedores,email|unique:users,email',
        'telefone' => 'required',
    ];
    public $table = 'fornecedores';
    public $fillable = [
        'codigo_mega',
        'nome',
        'cnpj',
        'tipo_logradouro',
        'logradouro',
        'numero',
        'complemento',
        'cidade_id',
        'municipio',
        'estado',
        'situacao_cnpj',
        'inscricao_estadual',
        'email',
        'site',
        'telefone',
        'cep',
        'user_id',
        'imposto_simples',
        'nome_socio',
        'nacionalidade_socio',
        'estado_civil_socio',
        'profissao_socio',
        'rg_socio',
        'cpf_socio',
        'endereco_socio',
        'cidade_socio',
        'estado_socio',
        'cep_socio',
        'telefone_socio',
        'celular_socio',
        'email_socio',
        'nome_vendedor',
        'email_vendedor',
        'telefone_vendedor'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo_mega' => 'integer',
        'nome' => 'string',
        'cnpj' => 'string',
        'tipo_logradouro' => 'string',
        'logradouro' => 'string',
        'numero' => 'string',
        'complemento' => 'string',
        'cidade_id' => 'integer',
        'municipio' => 'string',
        'estado' => 'string',
        'situacao_cnpj' => 'string',
        'inscricao_estadual' => 'string',
        'email' => 'string',
        'site' => 'string',
        'telefone' => 'string',
        'cep' => 'string',
        'user_id' => 'integer',
        'imposto_simples' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function catalogoContratos()
    {
        return $this->hasMany(CatalogoContrato::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedor()
    {
        return $this->hasMany(QcFornecedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function getIsUserAttribute()
    {
        return !is_null($this->user) && $this->user->active;
    }

    /**
     * faltaDados
     * Verifica se falta algum dos dados necessários
     * @return bool
     */
    public function faltaDados()
    {
        $falta_dados = false;
        $dados = [
            'nome_socio',
            'nacionalidade_socio',
            'estado_civil_socio',
            'profissao_socio',
            'rg_socio',
            'cpf_socio',
            'endereco_socio',
            'cidade_socio',
            'estado_socio',
            'cep_socio',
            'telefone_socio',
            'celular_socio',
            'email_socio',
            'nome_vendedor',
            'email_vendedor',
            'telefone_vendedor'
        ];
        // Percorre todos os campos do registro atual verificando se estão preenchidos
        array_map(function($value) use (&$falta_dados){
            if(!strlen($this->attributes[$value])){
                $falta_dados = true;
            }
        }, $dados);
        return $falta_dados;
    }

    public function faltaQuaisDados()
    {
        $dados_faltantes = [];
        $dados = [
            'nome_socio',
            'nacionalidade_socio',
            'estado_civil_socio',
            'profissao_socio',
            'rg_socio',
            'cpf_socio',
            'endereco_socio',
            'cidade_socio',
            'estado_socio',
            'cep_socio',
            'telefone_socio',
            'celular_socio',
            'email_socio',
            'nome_vendedor',
            'email_vendedor',
            'telefone_vendedor'
        ];
        // Percorre todos os campos do registro atual verificando se estão preenchidos
        array_map(function($value) use (&$dados_faltantes){
            if(!strlen($this->attributes[$value])){
                $dados_faltantes[] = $value;
            }
        }, $dados);
        return $dados_faltantes;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function servicos()
    {
        return $this->belongsToMany(FornecedorServico::class, 'fornecedor_servicos', 'codigo_fornecedor_id', 'codigo_servico_id');
    }

    public function fornecedores_associados(){
        return $this->belongsToMany(Fornecedor::class,'fornecedores_associados','fornecedor_id','fornecedor_associado_id')->withTimestamps();
    }
    public function fornecedores_associados_com_este(){
        return $this->belongsToMany(Fornecedor::class,'fornecedores_associados','fornecedor_associado_id','fornecedor_id')->withTimestamps();
    }

    /**
     * Busca todos os relacionamentos com fornecedores associados
     * @return Array_
     */
    public function fornecedores_associados_ids(){
        $fornecedores_associados_ids = $this->fornecedores_associados()
            ->select('fornecedores_associados.fornecedor_associado_id')
            ->pluck('fornecedor_associado_id', 'fornecedor_associado_id')
            ->toArray();
        $fornecedores_associados_ids2 = $this->fornecedores_associados_com_este()
            ->select('fornecedores_associados.fornecedor_id')
            ->pluck('fornecedor_id', 'fornecedor_id')
            ->toArray();
        $fornecedores_associados_ids += $fornecedores_associados_ids2;
        return $fornecedores_associados_ids;
    }
}
