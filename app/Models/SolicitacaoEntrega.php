<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoEntrega extends Model
{
    public $table = 'solicitacao_entregas';

    public $fillable = [
        'contrato_id',
        'user_id',
        'valor_total',
        'habilita_faturamento',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'contrato_id' => 'integer',
        'valor_total' => 'float',
        'habilita_faturamento' => 'boolean'
    ];

    public function itens()
    {
        return $this->hasMany(
            SolicitacaoEntregaItem::class,
            'solicitacao_entrega_id'
        );
    }

    public function contrato()
    {
        return $this->belongsTo(
            Contrato::class,
            'contrato_id'
        );
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

    public function aprova($aprovado)
    {
        $this->update(['aprovado' => $aprovado]);
    }

    public static $workflow_tipo_id = WorkflowTipo::SOLICITACAO_ENTREGA;

    public function workflowNotification()
    {
        return [
            'message' => 'Você tem uma nova Solicitação de Entrega para aprovar',
            'link' => route('solicitacao_entrega.show', $this->id)
        ];
    }
}
