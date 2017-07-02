<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeStatus extends Model
{
    const EM_APROVACAO = 1;
    const REPROVADO    = 2;
    const APROVADO     = 3;
    const CANCELADO    = 4;
    const REALIZADO    = 5;

    public $table = 'oc_status';

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
