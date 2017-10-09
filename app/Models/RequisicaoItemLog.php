<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequisicaoItemLog extends Model
{
    use SoftDeletes;

    public $table = 'requisicao_itens_log';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'requisicao_itens_id',
        'qtde_anterior',
        'qtde_nova',
        'status_id_anterior',
        'status_id_novo',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'requisicao_id' => 'integer',
        'qtde_anterior' => 'double',
        'qtde_nova' => 'double',
        'status_id_anterior' => 'string',
        'status_id_novo' => 'string',
        'user_id' => 'string'
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
    public function requisicaoItem()
    {
        return $this->belongsTo(\App\Models\RequisicaoItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
