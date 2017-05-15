<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SolicitacaoInsumo
 * @package App\Models\Admin
 * @version May 15, 2017, 3:47 pm BRT
 */
class SolicitacaoInsumo extends Model
{
    use SoftDeletes;

    public $table = 'solicitacao_insumos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'unidade_sigla',
        'codigo',
        'insumo_grupo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'unidade_sigla' => 'string',
        'codigo' => 'string',
        'insumo_grupo_id' => 'integer'
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
    public function insumoGrupo()
    {
        return $this->belongsTo(\App\Models\InsumoGrupo::class);
    }
}
