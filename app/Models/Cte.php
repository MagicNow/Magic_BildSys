<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Notafiscal
 * @package App\Models
 * @version June 28, 2017, 1:51 pm BRT
 */
class Cte extends Model
{
    public $table = 'ctes';

    public $timestamps = false;

    public $fillable = [
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function notas()
    {
        return $this->belongsToMany(\App\Models\Notafiscal::class, 'cte_notas', 'chave_nfe', 'cte_id');
    }

}
