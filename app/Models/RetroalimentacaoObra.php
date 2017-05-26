<?php

namespace App\Models;

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
        'origem',
        'categoria',
        'situacao_atual',
        'situacao_proposta',
        'acao',
        'data_prevista',
        'data_conclusao',
        'status',
        'resultado_obtido',
        'aceite'
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
        'origem' => 'string',
        'categoria' => 'string',
        'situacao_atual' => 'string',
        'situacao_proposta' => 'string',
        'acao' => 'string',
        'data_prevista' => 'date',
        'data_conclusao' => 'date',
        'status' => 'string',
        'resultado_obtido' => 'string'
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
}
