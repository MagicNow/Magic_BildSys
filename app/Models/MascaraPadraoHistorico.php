<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MascaraPadraoHistorico extends Model
{
    use SoftDeletes;

    public $table = 'mascara_padrao_historico';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'mascara_padrao_id',
        'user_id',
        'coeficiente_anterior',
        'coeficiente_atual',
        'observacao'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mascara_padrao_id' => 'integer',
        'user_id' => 'integer',
        'coeficiente_anterior' => 'float',
        'coeficiente_atual' => 'float',
        'observacao' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mascara_padrao_id' => 'required',
        'user_id' => 'required',
        'coeficiente_anterior' => 'required',
        'coeficiente_atual' => 'required'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

}
