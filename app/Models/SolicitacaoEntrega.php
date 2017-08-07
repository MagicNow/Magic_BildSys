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
        'se_status_id',
        'fornecedor_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                   => 'integer',
        'user_id'              => 'integer',
        'contrato_id'          => 'integer',
        'fornecedor_id'        => 'integer',
        'valor_total'          => 'float',
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

    public function status()
    {
        return $this->belongsTo(SeStatus::class, 'se_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
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
        return null;
    }

    public function aprova($aprovado)
    {
        $new_status = $aprovado
            ? SeStatus::APROVADO
            : SeStatus::REPROVADO
            ;

        $this->update(['se_status_id' => $new_status]);
    }

    public static $workflow_tipo_id = WorkflowTipo::SOLICITACAO_ENTREGA;
    
    public function workflowNotification()
    {
        return [
            'message' => 'Solicitação de Entrega '.$this->id.' à aprovar',
            'link' => url('/solicitacoes-de-entrega/'. $this->id),
            'workflow_tipo_id' => WorkflowTipo::SOLICITACAO_ENTREGA,
            'id_dinamico' => $this->id,
            'task'=>1,
            'done'=>0
        ];
    }

    public function workflowNotificationDone($aprovado)
    {
        return [
            'message' => 'Solicitação de Entrega '.$this->id.($aprovado?' aprovada ':' reprovada '),
            'link' => url('/solicitacoes-de-entrega/'. $this->id)
        ];
    }

    public function updateTotal()
    {
        $total = $this->itens()->sum('valor_total');

        $this->update(['valor_total' => $total]);

        return $total;
    }

    public function isStatus($status)
    {
        $status = is_array($status) ? $status : func_get_args();

        return in_array($this->se_status_id, $status);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function logs()
    {
        return $this->hasMany(SeStatusLog::class, 'solicitacao_entrega_id');
    }

    public function getTotalAttribute()
    {
        return $this->itens()->sum('valor_total');
    }

    public function getPodeEditarAttribute()
    {
        return $this->isStatus(SeStatus::REPROVADO);
    }
}
