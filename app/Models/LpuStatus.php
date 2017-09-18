<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpuStatus extends Model
{
    const MANUAL = 1;
    const AUTOMATICO    = 2;    

    public $table = 'lpu_status';

    public $timestamps = false;

    public $fillable = [
        'nome',
        'cor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'   => 'integer',
        'nome' => 'string',
        'cor'  => 'string'
    ];
}
