<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MemoriaCalculo
 * @package App\Models
 * @version June 28, 2017, 7:00 pm BRT
 */
class MemoriaCalculo extends Model
{
    use SoftDeletes;

    public $table = 'memoria_calculos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'padrao',
        'user_id',
        'modo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'user_id' => 'integer',
        'modo' => 'string'
    ];

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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function memoriaCalculoBlocos()
    {
        return $this->hasMany(\App\Models\MemoriaCalculoBloco::class);
    }
}
