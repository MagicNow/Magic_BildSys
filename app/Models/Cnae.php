<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cnae
 * @package App\Models
 * @version May 8, 2017, 12:29 pm BRT
 */
class Cnae extends Model
{
    use SoftDeletes;

    public $table = 'servicos_cnae';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id',
        'nome',
        'irrf',
        'inss',
        'csll',
        'pis',
        'cofins',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'     => 'integer',
        'nome'   => 'string',
        'irrf'   => 'float',
        'inss'   => 'float',
        'csll'   => 'float',
        'pis'    => 'float',
        'cofins' => 'float',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public static $iss = [
        2.00,
        2.79,
        3.50,
        3.84,
        3.87,
        4.23,
        4.26,
        4.31,
        4.61,
        4.65,
        5.00,
    ];

    public function getIssAttribute()
    {
        if(in_array($this->id, [172, 61, 59, 78])) {
            return 0;
        }

        return 2;
    }

    public function getIssSimplesAttribute()
    {
        return self::$iss;
    }
}
