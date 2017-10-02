<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentoFinanceiroTipo
 * @package App\Models
 * @version September 21, 2017, 3:50 pm -03
 */
class DocumentoFinanceiroTipo extends Model
{
    public $table = 'documento_financeiro_tipos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nome',
        'codigo_mega',
        'retem_irrf',
        'retem_impostos'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'codigo_mega' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
