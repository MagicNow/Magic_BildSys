<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoEqualizacaoTecnica
 * @package App\Models
 * @version April 25, 2017, 2:09 pm BRT
 */
class TipoEqualizacaoTecnica extends Model
{
    public $table = 'tipo_equalizacao_tecnicas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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
    public function anexos()
    {
        return $this->hasMany(\App\Models\EqualizacaoTecnicaAnexo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function itens()
    {
        return $this->hasMany(\App\Models\EqualizacaoTecnicaItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcTipoEqualizacaoTecnicas()
    {
        return $this->hasMany(\App\Models\QcTipoEqualizacaoTecnica::class);
    }
	
	public function carteiras()
    {
        return $this->belongsToMany(Carteira::class, 'carteira_tipo_equalizacao_tecnicas', 'tipo_equalizacao_id', 'carteira_id');
    }
}
