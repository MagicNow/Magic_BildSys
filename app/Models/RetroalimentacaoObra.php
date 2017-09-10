<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RetroalimentacaoObra
 * @package App\Models
 * @version April 28, 2017, 2:53 pm BRT
 */
class RetroalimentacaoObra extends Model
{
    use SoftDeletes;

    public $table = 'retroalimentacao_obras';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'obra_id',
        'user_id',
        'user_id_responsavel',
        'origem',
        'categoria_id',
        'situacao_atual',
        'situacao_proposta',
        'acao',
        'data_prevista',
        'data_conclusao',
        'aceite',
        'resultado_obtido',
        'status_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'user_id' => 'integer',
        'user_id_responsavel' => 'integer',
        'origem' => 'string',
        'categoria_id' => 'integer',
        'situacao_atual' => 'string',
        'situacao_proposta' => 'string',
        'acao' => 'string',
        'data_prevista' => 'date',
        'data_conclusao' => 'date',
        'aceite' => 'integer',
        'resultado_obtido' => 'string',
        'status_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'obra_id' => 'required'
    ];

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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function categoria()
    {
        return $this->belongsTo(\App\Models\RetroalimentacaoObraCategoria::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function status()
    {
        return $this->belongsTo(\App\Models\RetroalimentacaoObraStatus::class);
    }

    public function setDataPrevistaAttribute($value){
        if(strlen($value)){
            if(strpos($value,'/')){
                $this->attributes["data_prevista"] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            }else{
                $this->attributes["data_prevista"] = $value;
            }
        }else{
            $this->attributes["data_prevista"] = null;
        }
    }
    
    public function setDataConclusaoAttribute($value){
        if(strlen($value)){
            if(strpos($value,'/')){
                $this->attributes["data_conclusao"] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            }else{
                $this->attributes["data_conclusao"] = $value;
            }
        }else{
            $this->attributes["data_conclusao"] = null;
        }
    }
}
