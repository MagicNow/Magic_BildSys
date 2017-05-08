<?php

namespace App\Models;

use Storage;
use Eloquent as Model;

class QcEqualizacaoTecnicaAnexoExtra extends Model
{
    public $table = 'qc_equalizacao_tecnica_anexo_extra';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'quadro_de_concorrencia_id',
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
        'quadro_de_concorrencia_id' => 'integer',
        'arquivo' => 'string',
        'nome' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'arquivo' => 'required',
        'nome' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(\App\Models\QuadroDeConcorrencia::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->attributes['arquivo']);
    }
}
