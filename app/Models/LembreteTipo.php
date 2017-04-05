<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class LembreteTipo
 * @package App\Models
 * @version April 5, 2017, 12:32 pm BRT
 */
class LembreteTipo extends Model
{

    public $table = 'lembrete_tipos';


    public $fillable = [
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
        'nome' => 'required',
        'dias_prazo_minimo' => 'required',
        'dias_prazo_maximo' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lembretes()
    {
        return $this->hasMany(\App\Models\Lembrete::class);
    }
}
