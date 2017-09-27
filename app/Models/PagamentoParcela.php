<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PagamentoParcela
 * @package App\Models
 * @version September 21, 2017, 12:36 pm -03
 */
class PagamentoParcela extends Model
{
    use SoftDeletes;

    public $table = 'pagamento_parcelas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at','data_vencimento','data_base_multa'];


    public $fillable = [
        'valor',
        'pagamento_id',
        'numero_documento',
        'data_vencimento',
        'percentual_juro_mora',
        'valor_juro_mora',
        'percentual_multa',
        'valor_multa',
        'data_base_multa',
        'percentual_desconto',
        'valor_desconto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pagamento_id' => 'integer',
        'numero_documento' => 'string',
        'data_vencimento' => 'date',
        'data_base_multa' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'data_vencimento' => 'required',
        'valor' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pagamento()
    {
        return $this->belongsTo(Pagamento::class);
    }
}
