<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Requisicao
 * @package App\Models
 * @version September 22, 2017, 8:14 am -03
 */
class Requisicao extends Model
{
    use SoftDeletes;

    public $table = 'requisicao';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'obra_id',
        'user_id',
        'status_id',
        'local',
        'torre',
        'pavimento',
        'trecho',
        'andar',
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
        'local' => 'string',
        'torre' => 'string',
        'pavimento' => 'string',
        'trecho' => 'string',
        'andar' => 'string',
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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function requisicaoItens()
    {
        return $this->hasMany(\App\Models\RequisicaoItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function status()
    {
        return $this->belongsTo(\App\Models\RequisicaoStatus::class);
    }
}
