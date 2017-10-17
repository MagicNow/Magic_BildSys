<?php

namespace App\Models;

use Eloquent as Model;

class QtdMinima extends Model
{
    public $table = 'qtd_minimas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'qtd',
        'obra_id',
        'insumo_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qtd' => 'decimal',
        'obra_id' => 'integer',
        'insumo_id' => 'integer'

    ];

}
