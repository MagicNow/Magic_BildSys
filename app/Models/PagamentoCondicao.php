<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class PagamentoCondicao
 * @package App\Models
 * @version September 21, 2017, 12:34 pm -03
 */
class PagamentoCondicao extends Model
{
    public $table = 'pagamento_condicoes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'nome',
        'codigo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'codigo' => 'string'
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
