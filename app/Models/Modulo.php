<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 04/04/2017
 * Time: 12:14
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    public $table = 'modulos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

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