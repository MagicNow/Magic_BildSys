<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoLevantamento
 * @package App\Models
 * @version April 5, 2017, 12:32 pm BRT
 */
class TipoLevantamento extends Model
{

    public $table = 'tipo_levantamentos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
    ];

    public static $campos = [
        'nome',        
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
    public function MascaraPadraoInsumos()
    {
        return $this->hasMany(MascaraPadraoInsumo::class);
    }
}
