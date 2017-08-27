<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class LevantamentoTipo
 * @package App\Models
 * @version April 5, 2017, 12:32 pm BRT
 */
class LevantamentoTipo extends Model
{

    public $table = 'levantamento_tipos';

    public $timestamps = false;

    public $fillable = [
        'nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mascaraInsumos()
    {
        return $this->hasMany(MascaraInsumo::class);
    }
}
