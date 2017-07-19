<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Medicao
 * @package App\Models
 * @version July 11, 2017, 2:13 pm BRT
 */
class Medicao extends Model
{
    public $table = 'medicoes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'mc_medicao_previsao_id',
        'qtd',
        'medicao_servico_id',
        'user_id',
        'aprovado',
        'obs'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'mc_medicao_previsao_id' => 'integer',
        'medicao_servico_id' => 'integer',
        'user_id' => 'integer',
        'obs' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mc_medicao_previsao_id'=>'required',
        'qtd'=>'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mcMedicaoPrevisao()
    {
        return $this->belongsTo(McMedicaoPrevisao::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoImagens()
    {
        return $this->hasMany(MedicaoImagem::class,'medicao_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function medicaoServicos()
    {
        return $this->belongsTo(MedicaoServico::class);
    }
}
