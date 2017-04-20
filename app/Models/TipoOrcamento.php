<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 12:14
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TipoOrcamento extends Model
{
    public $table = 'orcamento_tipos';
    public $timestamps = false;
    
    public $fillable = [
        'id',
        'nome'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}