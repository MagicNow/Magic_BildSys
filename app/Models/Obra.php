<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 05/04/2017
 * Time: 18:19
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    public $table = 'obras';

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