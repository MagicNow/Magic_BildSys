<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotaFiscalItem
 * @package App\Models
 * @version July 20, 2017, 12:44 pm BRT
 */
class NotaFiscalFatura extends Model
{
    public $table = 'nota_fiscal_faturas';

    public $timestamps = false;

    public $fillable = [
        'nota_fiscal_id',
        'numero',
        'vencimento',
        'valor',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nota_fiscal_id' => 'integer',
        'numero' => 'string',
        'vencimento' => 'date',
        'valor' => 'float'
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
    public function notaFiscal()
    {
        return $this->belongsTo(\App\Models\NotaFiscal::class, 'nota_fiscal_id');
    }
}
