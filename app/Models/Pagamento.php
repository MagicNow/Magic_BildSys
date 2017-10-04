<?php

namespace App\Models;

use Carbon\Carbon;
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
        'contrato_id' => 'required',
        'fornecedor_id' => 'required',
        'pagamento_condicao_id' => 'required',
        'documento_tipo_id' => 'required',
        'data_emissao' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function documentoTipo()
    {
        return $this->belongsTo(DocumentoTipo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function notaFiscal()
    {
        return $this->belongsTo(Notafiscal::class, 'notas_fiscal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function obra()
    {
        return $this->belongsTo(Obra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pagamentoCondicao()
    {
        return $this->belongsTo(PagamentoCondicao::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function parcelas()
    {
        return $this->hasMany(PagamentoParcela::class);
    }

    public function setNotasFiscalIdAttribute($value){
        $this->attributes['notas_fiscal_id'] = intval($value) ? $value : null;
    }

    public function logs()
    {
        return $this->morphMany(LogIntegracao::class, 'loggable');
    }
}
