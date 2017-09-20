<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qc extends Model
{
    public $table = 'qc';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'obra_id',
        'tipologia',
        'carteira_id',
        'descricao',
        'valor_pre_orcamento',
        'valor_orcamento_inicial',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'obra_id' => 'integer',
        'tipologia' => 'string',
        'carteira_id' => 'integer',
        'descricao' => 'string',
        'valor_pre_orcamento' => 'float',
        'valor_orcamento_inicial' => 'float',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'obra_id' => 'required|integer',
        'tipologia' => 'required|in:Budget,Aditivo,Bild Design,Getec',
        'carteira_id' => 'required|integer',
        'descricao' => 'required',
        'valor_pre_orcamento' => 'required',
        'valor_orcamento_inicial' => 'required'
    ];
}
