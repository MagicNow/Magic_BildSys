<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class InsumoGrupo
 * @package App\Models
 * @version April 12, 2017, 1:48 pm BRT
 */
class InsumoGrupo extends Model
{

    public $table = 'insumo_grupos';
    
    public $timestamps = false;


    public $fillable = [
        'codigo_identificador',
        'nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'codigo_identificador' => 'string',
        'nome' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lembretes()
    {
        return $this->hasMany(Lembrete::class);
    }
}
