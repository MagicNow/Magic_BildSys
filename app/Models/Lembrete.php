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
        'user_id',
        'nome',
        'dias_prazo_minimo',
        'dias_prazo_maximo',
        'insumo_grupo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'lembrete_tipo_id' => 'integer',
        'user_id' => 'integer',
        'nome' => 'string',
        'dias_prazo_minimo' => 'integer',
        'dias_prazo_maximo' => 'integer',
        'insumo_grupo_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lembrete_tipo_id' => 'required',
        'nome' => 'required',
        'insumo_grupo_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumoGrupo()
    {
        return $this->belongsTo(InsumoGrupo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lembreteTipo()
    {
        return $this->belongsTo(LembreteTipo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
//    public function lembreteNotificaPerfis()
//    {
//        return $this->hasMany(LembreteNotificaPerfi::class);
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
//    public function lembreteNotificaUsuarios()
//    {
//        return $this->hasMany(LembreteNotificaUsuario::class);
//    }
}
