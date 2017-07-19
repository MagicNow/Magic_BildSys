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
        'qtd_funcionarios',
        'qtd_ajudantes',
        'qtd_outros',
        'descontos',
        'descricao_descontos',
        'periodo_inicio',
        'periodo_termino',
        'contrato_item_apropriacao_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qtd_funcionarios' => 'integer',
        'qtd_ajudantes' => 'integer',
        'qtd_outros' => 'integer',
        'descricao_descontos' => 'string',
        'periodo_inicio' => 'date',
        'periodo_termino' => 'date',
        'contrato_item_apropriacao_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'periodo_inicio' => 'required',
        'periodo_termino' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function medicao()
    {
        return $this->hasMany(Medicao::class);
    }

    public function contratoItemApropriacao(){
        return $this->belongsTo(ContratoItemApropriacao::class,'contrato_item_apropriacao_id');
    }
}
