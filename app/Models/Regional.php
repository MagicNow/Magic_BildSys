<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Regional
 * @package App\Models
 * @version August 4, 2017, 2:32 pm BRT
 */
class Regional extends Model
{
    use SoftDeletes;

    public $table = 'regionais';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
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
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lpu()
    {
        return $this->hasMany(Lpu::class);
    }

    
}
