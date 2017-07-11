<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoServico
 * @package App\Models
 * @version July 11, 2017, 2:21 pm BRT
 */
class MedicaoServico extends Model
{
    public $table = 'medicao_servicos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'medicao_id',
        'qtd_funcionarios',
        'qtd_ajudantes',
        'qtd_outros',
        'descontos',
        'descricao_descontos',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'medicao_id' => 'integer',
        'qtd_funcionarios' => 'integer',
        'qtd_ajudantes' => 'integer',
        'qtd_outros' => 'integer',
        'descricao_descontos' => 'string',
        'user_id' => 'integer'
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
    public function medicao()
    {
        return $this->belongsTo(Medicao::class);
    }
}
