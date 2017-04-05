<?php
/**
 * Created by PhpStorm.
 * User: Raul
 * Date: 05/04/2017
 * Time: 14:07
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Planilha extends Model
{
    public $table = 'planilhas';

    public $fillable = [
        'id',
        'user_id',
        'arquivo',
        'json',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'arquivo' => 'string',
        'json' => 'string',
        'status' => 'string'

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];
}