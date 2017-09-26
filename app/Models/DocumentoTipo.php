<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DocumentoTipo
 * @package App\Models
 * @version September 21, 2017, 12:35 pm -03
 */
class DocumentoTipo extends Model
{
    public $table = 'documento_tipos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'nome',
        'codigo_mega',
        'sigla'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'sigla' => 'string',
        'codigo_mega' => 'integer'
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
    public function pagamentos()
    {
        return $this->hasMany(\App\Models\Pagamento::class);
    }
}
