<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TarefaMascara
 * @package App\Models
 * @version April 12, 2017, 1:52 pm BRT
 */
class TarefaMascara extends Model
{
    use SoftDeletes;

    public $table = 'tarefa_mascaras';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'obra_id',
		'mascara_padrao_id',
		'tarefa_padrao_id',
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
        'obra_id' => 'integer',
		'mascara_padrao_id' => 'integer',
		'tarefa_padrao_id' => 'integer',
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
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mascaraPadrao()
    {
        return $this->belongsTo(MascaraPadrao::class);
    }
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tarefaPadrao()
    {
        return $this->belongsTo(TarefaPadrao::class);
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

}
