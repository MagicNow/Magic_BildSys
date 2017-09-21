<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Pagamento
 * @package App\Models
 * @version September 21, 2017, 12:35 pm -03
 */
class Pagamento extends Model
{
    use SoftDeletes;

    public $table = 'pagamentos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'contrato_id',
        'obra_id',
        'numero_documento',
        'fornecedor_id',
        'data_emissao',
        'valor',
        'pagamento_condicao_id',
        'documento_tipo_id',
        'notas_fiscal_id',
        'enviado_integracao',
        'integrado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_id' => 'integer',
        'obra_id' => 'integer',
        'fornecedor_id' => 'integer',
        'data_emissao' => 'date',
        'pagamento_condicao_id' => 'integer',
        'documento_tipo_id' => 'integer',
        'notas_fiscal_id' => 'integer'
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
    public function contrato()
    {
        return $this->belongsTo(\App\Models\Contrato::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function documentoTipo()
    {
        return $this->belongsTo(\App\Models\DocumentoTipo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedore()
    {
        return $this->belongsTo(\App\Models\Fornecedore::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function notasFiscai()
    {
        return $this->belongsTo(\App\Models\NotasFiscai::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(\App\Models\Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pagamentoCondico()
    {
        return $this->belongsTo(\App\Models\PagamentoCondico::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pagamentoParcelas()
    {
        return $this->hasMany(\App\Models\PagamentoParcela::class);
    }
}
