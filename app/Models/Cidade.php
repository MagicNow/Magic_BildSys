<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ObraUser
 * @package App\Models
 * @version April 25, 2017, 6:11 pm BRT
 */
class Cidade extends Model
{

    public $table = 'cidades';
    public $timestamps = false;

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
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }
}
