<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class EqualizacaoTecnicaItem
 * @package App\Models
 * @version April 25, 2017, 4:18 pm BRT
 */
class EqualizacaoTecnicaItem extends Model
{
    public $table = 'equalizacao_tecnica_itens';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'tipo_equalizacao_tecnica_id',
        'nome',
        'descricao',
        'obrigatorio'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'tipo_equalizacao_tecnica_id' => 'integer',
        'nome' => 'string',
        'descricao' => 'string'
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

    public function checks()
    {
        return $this->morphMany(QcFornecedorEqualizacaoCheck::class, 'checkable');
    }
}
