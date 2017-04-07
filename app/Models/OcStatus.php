<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class oc_status
 * @package App\Models
 * @version April 7, 2017, 11:38 am BRT
 */
class OcStatus extends Model
{
    public $table = 'oc_status';
    
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
        'nome' => 'string'
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
    public function ordemDeCompra()
    {
        return $this->hasMany(\App\Models\OrdemDeCompra::class);
    }
    
}
