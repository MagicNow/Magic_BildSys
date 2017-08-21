<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 15/08/2017
 * Time: 12:14
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class TipoCronogramaFisico extends Model
{
    public $table = 'cronograma_fisico_tipos';
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