<?php

namespace App\Models;

use Storage;
use Eloquent as Model;

/**
 * Class EqualizacaoTecnicaAnexo
 * @package App\Models
 * @version April 25, 2017, 4:31 pm BRT
 */
class EqualizacaoTecnicaAnexo extends Model
{
    public $table = 'equalizacao_tecnica_anexos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'tipo_equalizacao_tecnica_id',
        'arquivo',
        'nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo_equalizacao_tecnica_id' => 'integer',
        'arquivo' => 'string',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoEqualizacaoTecnica()
    {
        return $this->belongsTo(\App\Models\TipoEqualizacaoTecnica::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->attributes['arquivo']);
    }
}
