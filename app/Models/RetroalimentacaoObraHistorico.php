<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetroalimentacaoObraHistorico extends Model
{
    use SoftDeletes;

    public $table = 'retroalimentacao_obras_historico';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'retroalimentacao_obras_id',
        'user_id_origem',
        'user_id_destino',
        'status_origem',
        'status_destino',
        'andamento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'retroalimentacao_obras_id' => 'integer',
        'user_id_origem' => 'integer',
        'user_id_destino' => 'integer',
        'status_origem' => 'string',
        'status_destino' => 'string',
        'andamento' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'retroalimentacao_obras_id' => 'required',
        'user_id_origem' => 'required',
        'user_id_destino' => 'required',
        'status_destino' => 'required',
        'andamento' => 'required'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function userOrigem()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_origem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function userDestino()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_destino');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function statusOrigem()
    {
        return $this->belongsTo(\App\Models\RetroalimentacaoObraStatus::class, 'status_origem');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function statusDestino()
    {
        return $this->belongsTo(\App\Models\RetroalimentacaoObraStatus::class, 'status_destino');
    }


}
