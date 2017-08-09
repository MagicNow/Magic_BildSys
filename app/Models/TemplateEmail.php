<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TemplateEmail
 * @package App\Models
 * @version August 9, 2017, 11:10 am -03
 */
class TemplateEmail extends Model
{
    use SoftDeletes;

    public $table = 'template_emails';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'chave',
        'valor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'chave' => 'string',
        'valor' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'chave' => 'required',
        'valor' => 'required'
    ];

    
}
