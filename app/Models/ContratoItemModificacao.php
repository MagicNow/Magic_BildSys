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

    public static $workflow_tipo_id = WorkflowTipo::ITEM_CONTRATO;

    public function workflowNotification()
    {
        return [
            'message' => "Modificação do Contrato {$this->item->contrato_id} à aprovar",
            'link' => route('contratos.show', $this->item->contrato_id),
            'workflow_tipo_id' => WorkflowTipo::ITEM_CONTRATO,
            'id_dinamico' => $this->id,
            'task'=>1,
            'done'=>0
        ];
    }
    public function workflowNotificationDone($aprovado)
    {
        return [
            'message' => 'Modificação do Contrato {$this->item->contrato_id} '.$this->id.($aprovado?' aprovada ':' reprovada '),
            'link' => route('contratos.show', $this->item->contrato_id),
        ];
    }

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
        'anexo',
        'user_id',
        'descricao'
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
    public static $rules = [];

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

    public function idPai(){
        return null;
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
        return $this->item->contrato->obra_id;
    }

    public function aprova($isAprovado)
    {
        $this->attributes['contrato_status_id'] = $isAprovado
            ? ContratoStatus::APROVADO
            : ContratoStatus::REPROVADO;

        if($isAprovado) {
            $this->item->applyChanges($this);
            $this->item->contrato->updateTotal();
        }

        $this->apropriacoes->map(function($apropriacao) {
            $apropriacao->update(['qtd' => $apropriacao->pivot->qtd_atual]);
        });

        $this->item->update(['pendente' => 0]);

        $this->save();

        ContratoItemModificacaoLog::create([
            'contrato_item_modificacao_id' => $this->id,
            'contrato_status_id'           => $this->contrato_status_id,
            'user_id'                      => auth()->id()
        ]);
    }

    public function dataUltimoPeriodoAprovacao(){
        $ultimoStatusAprovacao = $this->logs()->where('contrato_status_id',ContratoStatus::EM_APROVACAO)
            ->orderBy('created_at','DESC')->first();
        if($ultimoStatusAprovacao){
            return $ultimoStatusAprovacao->created_at;
        }
        return null;
    }

    public function getValorTotalAttribute()
    {
        return (float) $this->valor_unitario_atual * (float) $this->qtd_atual;
    }

    public function apropriacoes()
    {
        return $this->belongsToMany(
            ContratoItemApropriacao::class,
            'contrato_item_modificacao_apropriacao',
            'contrato_item_modificacao_id',
            'contrato_item_apropriacao_id'
        )
        ->withPivot([ 'qtd_atual', 'qtd_anterior', 'id', 'descricao' ])
        ->withTimestamps();
    }
}
