<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MemoriaCalculo
 * @package App\Models
 * @version June 29, 2017, 4:47 pm BRT
 */
class MemoriaCalculo extends Model
{

    public $table = 'memoria_calculos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


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
        'nome' => 'required',
        'modo' => 'required',
        'blocos' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function blocos()
    {
        return $this->hasMany(MemoriaCalculoBloco::class);
    }
}
