<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Requisicao
 * @package App\Models
 * @version September 22, 2017, 8:14 am -03
 */
class RequisicaoItem extends Model
{
    use SoftDeletes;

    public $table = 'requisicao_itens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'requisicao_id',
        'estoque_id',
        'unidade',
        'qtde',
        'torre',
        'pavimento',
        'trecho',
        'andar',
        'apartamento',
        'comodo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'requisicao_id' => 'integer',
        'estoque_id' => 'integer',
        'unidade' => 'string',
        'qtde' => 'double',
        'torre' => 'string',
        'pavimento' => 'string',
        'trecho' => 'string',
        'andar' => 'string',
        'apartamento' => 'string',
        'comodo' => 'string',
        'status_id' => 'integer',

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
    public function requisicao()
    {
        return $this->belongsTo(\App\Models\Requisicao::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function estoque()
    {
        return $this->belongsTo(\App\Models\Estoque::class);
    }

}
