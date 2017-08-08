<?php

namespace App\Models;

use App\Repositories\ContratoRepository;
use Eloquent as Model;
use Laracasts\Flash\Flash;
use App\Models\ContratoStatus;
use App\Models\SeStatus;

/**
 * Class Contrato
 * @package App\Models
 * @version May 18, 2017, 6:06 pm BRT
 */
class Contrato extends Model
{
    public $table = 'contratos';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'contrato_status_id',
        'obra_id',
        'quadro_de_concorrencia_id',
        'fornecedor_id',
        'valor_total_inicial',
        'valor_total_atual',
        'contrato_template_id',
        'arquivo',
        'campos_extras'
    ];

    public static $workflow_tipo_id = WorkflowTipo::CONTRATO;

    public function workflowNotification()
    {
        return [
            'message' => 'Contrato '.$this->id.' à aprovar',
            'link' => route('contratos.show', $this->id),
            'workflow_tipo_id' => WorkflowTipo::CONTRATO,
            'id_dinamico' => $this->id,
            'task'=>1,
            'done'=>0
        ];
    }
    public function workflowNotificationDone($aprovado)
    {
        return [
            'message' => 'Contrato '.$this->id.($aprovado?' aprovado ':' reprovado '),
            'link' => route('contratos.show', $this->id),
        ];
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'contrato_status_id' => 'integer',
        'obra_id' => 'integer',
        'quadro_de_concorrencia_id' => 'integer',
        'fornecedor_id' => 'integer',
        'contrato_template_id' => 'integer',
        'arquivo' => 'string'
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
        return $this->belongsTo(ContratoStatus::class, 'contrato_status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contratoTemplate()
    {
        return $this->belongsTo(ContratoTemplate::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
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
    public function quadroDeConcorrencia()
    {
        return $this->belongsTo(QuadroDeConcorrencia::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function itens()
    {
        return $this->hasMany(ContratoItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function materiais()
    {
        return $this->hasMany(ContratoItem::class)
            ->whereHas('insumo', function($query) {
                $query->whereHas('insumoGrupo', function($query) {
                    $query->where('nome', 'like', 'MATERIAL%');
                });
            });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function entregas()
    {
        return $this->hasMany(SolicitacaoEntrega::class, 'contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function logs()
    {
        return $this->hasMany(ContratoStatusLog::class, 'contrato_id');
    }

    // Funções de Aprovações

    public function irmaosIds()
    {
        return [$this->attributes['id'] => $this->attributes['id']];
    }

    public function idPai(){
        return null;
    }

    public function aprovacoes()
    {
        return $this->morphMany(WorkflowAprovacao::class, 'aprovavel');
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
        return $this->attributes['obra_id'];
    }

    public function aprova($isAprovado)
    {
        $this->attributes['contrato_status_id'] = $isAprovado
            ? ContratoStatus::APROVADO
            : ContratoStatus::REPROVADO;

        $this->save();

        ContratoStatusLog::create([
            'contrato_id'        => $this->attributes['id'],
            'contrato_status_id' => $this->attributes['contrato_status_id'],
            'user_id'            => auth()->id()
        ]);

        // Verifica necessidade de assinar contrato e enviar ao fornecedor
        if ($this->hasServico() && $isAprovado) {
            // Muda o status
            $this->attributes['contrato_status_id'] = 4;

            $this->save();

            ContratoStatusLog::create([
                'contrato_id'        => $this->attributes['id'],
                'contrato_status_id' => $this->attributes['contrato_status_id'],
                'user_id'            => auth()->id()
            ]);
            // Notifica Fornecedor
            $retorno = ContratoRepository::notifyFornecedor($this->attributes['id']);
            if (!$retorno['success']) {
                Flash::error($retorno['messages'][0]);
            } else {
                if (isset($retorno['messages'])) {
                    Flash::success($retorno['messages'][0]);
                }
            }
        }elseif ($isAprovado){
            // Não tem serviço, Muda o status para Ativo
            $this->attributes['contrato_status_id'] = 5;

            $this->save();

            ContratoStatusLog::create([
                'contrato_id'        => $this->attributes['id'],
                'contrato_status_id' => $this->attributes['contrato_status_id'],
                'user_id'            => auth()->id()
            ]);
        }
    }

    public function hasServico()
    {
        return $this->itens
            ->pluck('insumo')
            ->pluck('insumoGrupo')
            ->pluck('nome')
            ->contains(function ($nome) {
                return starts_with($nome, 'SERVIÇO');
            });
    }

    public function hasMaterial()
    {
        return $this->itens
            ->pluck('insumo')
            ->pluck('insumoGrupo')
            ->pluck('nome')
            ->contains(function($nome) {
                return starts_with($nome, 'MATERIAL');
            });
    }

    public function isStatus($status)
    {
        $status = is_array($status) ? $status : func_get_args();

        return in_array($this->contrato_status_id, $status);
    }

    public function updateTotal()
    {
//        $itens = $this->itens()->whereHas('modificacoes', function ($q) {
//            $q->where('contrato_status_id', ContratoStatus::APROVADO);
//        })->get();
        $itens = $this->itens()->get();

        $this->update(['valor_total_atual' => $itens->sum('valor_total')]);

        return $this;
    }

    public function getValorTotalAttribute()
    {
        return $this->valor_total_atual;
    }

    public function getEmAprovacaoAttribute()
    {
        return $this->isStatus(ContratoStatus::EM_APROVACAO);
    }

    public function getPodeSolicitarEntregaAttribute()
    {
        return $this->isStatus(ContratoStatus::APROVADO, ContratoStatus::ATIVO) && $this->hasMaterial();
    }

    public function getPodeSolicitarNovaEntregaAttribute()
    {
        return $this->pode_solicitar_entrega && !$this->entregas()
            ->whereNotIn('se_status_id', [SeStatus::REALIZADO, SeStatus::CANCELADO])
            ->count();
    }

    public function getHasMaterialFaturamentoDiretoAttribute()
    {
        return $this->itens->pluck('insumo')->pluck('codigo')->contains(30019);
    }
}
