<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlanejamentoData
 * @package App\Models
 * @version October 17, 2017, 5:44 pm -02
 */
class PlanejamentoData extends Model
{
    use SoftDeletes;

    public $table = 'planejamento_datas';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'planejamento_id',
        'data',
        'data_fim'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'planejamento_id' => 'integer',
        'data' => 'date',
        'data_fim' => 'date'
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
    public function planejamento()
    {
        return $this->belongsTo(\App\Models\Planejamento::class);
    }
}
