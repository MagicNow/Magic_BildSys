<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlanejamentoCompra
 * @package App\Models
 * @version April 12, 2017, 1:52 pm BRT
 */
class PlanejamentoCompra extends Model
{
    use SoftDeletes;

    public $table = 'planejamento_compras';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'planejamento_id',
        'insumo_id',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id',
        'trocado_de',
        'codigo_estruturado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'planejamento_id' => 'integer',
        'insumo_id' => 'integer',
        'grupo_id'=> 'integer',
        'subgrupo1_id'=> 'integer',
        'subgrupo2_id'=> 'integer',
        'subgrupo3_id'=> 'integer',
        'servico_id'=> 'integer',
        'trocado_de'=> 'integer',
        'codigo_estruturado'=> 'string'
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
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo1()
    {
        return $this->belongsTo(Grupo::class,'subgrupo1_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo2()
    {
        return $this->belongsTo(Grupo::class,'subgrupo2_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo3()
    {
        return $this->belongsTo(Grupo::class,'subgrupo3_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function troca()
    {
        return $this->belongsTo(PlanejamentoCompra::class,'trocado_de');
    }
}
