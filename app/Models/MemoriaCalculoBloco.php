<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MemoriaCalculoBloco
 * @package App\Models
 * @version June 28, 2017, 7:00 pm BRT
 */
class MemoriaCalculoBloco extends Model
{

    public $table = 'memoria_calculo_blocos';
    
    public $timestamps = false;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'memoria_calculo_id',
        'estrutura',
        'pavimento',
        'trecho',
        'ordem'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'memoria_calculo_id' => 'integer',
        'estrutura' => 'integer',
        'pavimento' => 'integer',
        'trecho' => 'integer',
        'ordem' => 'integer',
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
    public function estruturaObj()
    {
        return $this->belongsTo(NomeclaturaMapa::class, 'estrutura');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function memoriaCalculo()
    {
        return $this->belongsTo(MemoriaCalculo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function trechoObj()
    {
        return $this->belongsTo(NomeclaturaMapa::class,'trecho');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pavimentoObj()
    {
        return $this->belongsTo(NomeclaturaMapa::class,'pavimento');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mcMedicaoPrevisoes()
    {
        return $this->hasMany(McMedicaoPrevisao::class);
    }
}
