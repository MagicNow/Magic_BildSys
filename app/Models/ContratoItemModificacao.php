<?php

namespace App\Models;

use Eloquent as Model;
use App\Repositories\WorkflowAprovacaoRepository;

/**
 * Class ContratoItemModificacao
 * @package App\Models
 * @version May 18, 2017, 6:09 pm BRT
 */
class ContratoItemModificacao extends Model
{
    public $table = 'contrato_item_modificacoes';

    public static $workflow_tipo_id = WorkflowTipo::CONTRATO;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contrato_item_id',
        'qtd_anterior',
        'qtd_atual',
        'valor_unitario_anterior',
        'valor_unitario_atual',
        'tipo_modificacao',
        'contrato_status_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_item_id' => 'integer',
        'tipo_modificacao' => 'string',
        'contrato_status_id' => 'integer',
        'user_id' => 'integer'
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
    public function status()
    {
        return $this->belongsTo(ContratoStatus::class);
    }

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
    public function logs()
    {
        return $this->hasMany(ContratoItemModificacaoLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function item()
    {
        return $this->belongsTo(ContratoItem::class, 'contrato_item_id');
    }

    public function aprovacoes()
    {
        return $this->morphMany(WorkflowAprovacao::class, 'aprovavel');
    }

    public function irmaosIds()
    {
        return [$this->attributes['id'] => $this->attributes['id']];
    }

    public function paiEmAprovacao()
    {
        return false;
    }

    public function confereAprovacaoGeral()
    {
        return false;
    }

    public function qualObra()
    {
        return null;
    }

    public function aprova($isAprovado)
    {
        $this->attributes['contrato_status_id'] = $isAprovado
            ? ContratoStatus::APROVADO
            : ContratoStatus::REPROVADO;

        $this->save();

        ContratoItemModificacaoLog::create([
            'contrato_id'        => $this->attributes['id'],
            'contrato_status_id' => $this->attributes['contrato_status_id'],
            'user_id'            => auth()->id()
        ]);
    }
}
