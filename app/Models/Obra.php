<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Obra
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class Obra extends Model
{
    use SoftDeletes;

    public $table = 'obras';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'cidade_id',
        'area_terreno',
        'area_privativa',
        'area_construida',
        'eficiencia_projeto',
        'num_apartamentos',
        'num_torres',
        'num_pavimento_tipo',
        'data_inicio',
        'data_cliente',
        'indice_bild_pre',
        'indice_bild_oi',
        'razao_social',
        'cnpj',
        'inscricao_estadual',
        'endereco_faturamento',
        'endereco_obra',
        'entrega_nota_fisca_e_boleto',
        'adm_obra_nome',
        'adm_obra_email',
        'eng_obra_nome',
        'eng_obra_email',
        'horario_entrega_na_obra',
        'referencias_bancarias',
        'referencias_comerciais',
        'logo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'cidade_id' => 'integer',
        'area_terreno' => 'decimal',
        'area_privativa' => 'decimal',
        'area_construida' => 'decimal',
        'eficiencia_projeto' => 'decimal',
        'num_apartamentos' => 'decimal',
        'num_torres' => 'decimal',
        'num_pavimento_tipo' => 'decimal',
        'data_inicio' => 'date',
        'data_cliente' => 'date',
        'indice_bild_pre' => 'decimal',
        'indice_bild_oi' => 'decimal',
        'razao_social' => 'string',
        'cnpj' => 'string',
        'inscricao_estadual' => 'string',
        'endereco_faturamento' => 'string',
        'endereco_obra' => 'string',
        'entrega_nota_fisca_e_boleto' => 'string',
        'adm_obra_nome' => 'string',
        'adm_obra_email' => 'string',
        'eng_obra_nome' => 'string',
        'eng_obra_email' => 'string',
        'horario_entrega_na_obra' => 'string',
        'referencias_bancarias' => 'string',
        'referencias_comerciais' => 'string',
        'logo' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function getAreaTerrenoAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setAreaTerrenoAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['area_terreno'] = $result;
    }

    public function getAreaPrivativaAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setAreaPrivativaAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['area_privativa'] = $result;
    }

    public function getAreaConstruidaAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setAreaConstruidaAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['area_construida'] = $result;
    }

    public function getEficienciaProjetoAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setEficienciaProjetoAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['eficiencia_projeto'] = $result;
    }

    public function getNumApartamentosAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setNumApartamentosAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['num_apartamentos'] = $result;
    }

    public function getNumTorresAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setNumTorresAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['num_torres'] = $result;
    }

    public function getNumPavimentoTipoAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setNumPavimentoTipoAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['num_pavimento_tipo'] = $result;
    }

    public function getIndiceBildPreAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setIndiceBildPreAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['indice_bild_pre'] = $result;
    }

    public function getIndiceBildOiAttribute($value)
    {
        if(strlen($value) == 4){
            $value = '0'.$value;
        }

        return number_format($value,2,',','.');
    }

    public function setIndiceBildOiAttribute($value)
    {
        $pontos = array(",");
        $value = str_replace('.','',$value);
        $result = str_replace( $pontos, ".", $value);

        $this->attributes['indice_bild_oi'] = $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cidade()
    {
        return $this->belongsTo(\App\Models\Cidade::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contratos()
    {
        return $this->hasMany(\App\Models\Contrato::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function obraUsers()
    {
        return $this->belongsToMany(\App\Models\ObraUser::class,'obra_users','obra_id','user_id')->withPivot('deleted_at')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function orcamentos()
    {
        return $this->hasMany(\App\Models\Orcamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraItens()
    {
        return $this->hasMany(\App\Models\OrdemDeCompraIten::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompras()
    {
        return $this->hasMany(\App\Models\OrdemDeCompra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function planejamentos()
    {
        return $this->hasMany(\App\Models\Planejamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function retroalimentacaoObras()
    {
        return $this->hasMany(\App\Models\RetroalimentacaoObra::class);
    }
}
