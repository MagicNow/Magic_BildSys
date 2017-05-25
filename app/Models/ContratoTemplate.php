<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ContratoTemplate
 * @package App\Models
 * @version May 18, 2017, 6:06 pm BRT
 */
class ContratoTemplate extends Model
{

    public $table = 'contrato_templates';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'template',
        'user_id',
        'campos_extras'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'template' => 'string',
        'user_id' => 'integer',
        'campos_extras' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required',
        'template' => 'required',
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
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function setTemplateAttribute($value){
        $this->attributes['template'] = str_replace('\\"','"',$value);
    }
}
