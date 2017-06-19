<?php

namespace App\Models;

use App\Repositories\ContratoRepository;
use Eloquent as Model;
use Laracasts\Flash\Flash;

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
        'valor_total',
        'contrato_template_id',
        'arquivo',
        'campos_extras'
    ];

    public static $workflow_tipo_id = WorkflowTipo::CONTRATO;

    public function workflowNotification()
    {
        return [
            'message' => 'Você tem um contrato para aprovar',
            'link' => route('contratos.show', $this->id)
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
    public function logs()
    {
        return $this->hasMany(ContratoStatusLog::class, 'contrato_id');
    }

    // Funções de Aprovações

    public function irmaosIds()
    {
        return [$this->attributes['id'] => $this->attributes['id']];
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

    public function isStatus($status)
    {
        $status = is_array($status) ? $status : func_get_args();

        return in_array($this->contrato_status_id, $status);
    }

    public function updateTotal()
    {
        $itens = $this->itens()->whereHas('modificacoes', function ($q) {
            $q->where('contrato_status_id', ContratoStatus::APROVADO);
        })->get();


        $this->update(['valor_total' => $itens->sum('valor_total')]);

        return $this;
    }
}
