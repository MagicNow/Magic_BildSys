<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Lembrete
 * @package App\Models
 * @version April 5, 2017, 12:30 pm BRT
 */
class Lembrete extends Model
{
    use SoftDeletes;

    public $table = 'lembretes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'lembrete_tipo_id',
        'planejamento_id',
        'user_id',
        'nome',
        'dias_prazo_minimo',
        'dias_prazo_maximo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lembrete_tipo_id' => 'integer',
        'planejamento_id' => 'integer',
        'user_id' => 'integer',
        'nome' => 'string',
        'dias_prazo_minimo' => 'integer',
        'dias_prazo_maximo' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lembrete_tipo_id' => 'required',
        'planejamento_id' => 'required',
        'nome' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lembreteTipo()
    {
        return $this->belongsTo(\App\Models\LembreteTipo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function planejamento()
    {
        return $this->belongsTo(\App\Models\Planejamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function lembreteNotificaPerfis()
//    {
//        return $this->hasMany(\App\Models\LembreteNotificaPerfi::class);
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     **/
//    public function lembreteNotificaUsuarios()
//    {
//        return $this->hasMany(\App\Models\LembreteNotificaUsuario::class);
//    }
}
