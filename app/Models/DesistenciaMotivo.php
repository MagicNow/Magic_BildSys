<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DesistenciaMotivo
 * @package App\Models
 * @version May 4, 2017, 3:17 pm BRT
 */
class DesistenciaMotivo extends Model
{
    public $table = 'desistencia_motivos';

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
    public static $rules = [ ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedors()
    {
        return $this->hasMany(\App\Models\QcFornecedor::class);
    }
}
