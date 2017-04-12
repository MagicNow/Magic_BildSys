<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdemDeCompra
 * @package App\Models
 * @version April 4, 2017, 5:25 pm BRT
 */
class OrdemDeCompra extends Model
{
    use SoftDeletes;

    public $table = 'ordem_de_compras';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'oc_status_id',
        'obra_id',
        'user_id',
        'aprovado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'oc_status_id' => 'integer',
        'obra_id' => 'integer',
        'aprovado' => 'integer',
        'user_id' => 'integer'
    ];

    public static $filters = [
        'obra-foreign_key-Obra-nome-id' => 'Obra',
        'oc_status_id-foreign_key-OcStatus-nome-id' => 'Status',
        'aprovado-boolean' => 'Aprovado',
        'created_at-date' => 'Criado em',
        'updated_at-date' => 'Atualizado em'
    ];

    public static $filters_insumos = [
        'grupo_id-foreign_key-Grupo-nome-id' => 'Grupo',
        'nome-string' => 'Nome'
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
    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function ocStatus()
    {
        return $this->belongsTo(OcStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function itens()
    {
        return $this->hasMany(OrdemDeCompraItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraStatusLogs()
    {
        return $this->hasMany(OrdemDeCompraStatusLog::class);
    }
}
