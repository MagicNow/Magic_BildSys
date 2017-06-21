<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Repositories\QuadroDeConcorrenciaRepository;

/**
 * Class OrdemDeCompraItem
 * @package App\Models
 * @version April 11, 2017, 2:52 pm BRT
 */
class OrdemDeCompraItem extends Model
{
    use SoftDeletes;

    public static $workflow_tipo_id = WorkflowTipo::OC;

    public function workflowNotification()
    {
        return [
            'message' => 'Você tem uma ordem de compra para aprovar',
            'link' => route('ordens_de_compra.detalhes', $this->ordem_de_compra_id)
        ];
    }

    public $table = 'ordem_de_compra_itens';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'ordem_de_compra_id',
        'obra_id',
        'codigo_insumo',
        'qtd',
        'valor_unitario',
        'valor_total',
        'aprovado',
        'obs',
        'justificativa',
        'tems',
        'grupo_id',
        'subgrupo1_id',
        'subgrupo2_id',
        'subgrupo3_id',
        'servico_id',
        'insumo_id',
        'emergencial',
        'sugestao_data_uso',
        'sugestao_contrato_id',
        'user_id',
        'unidade_sigla'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                   => 'integer',
        'ordem_de_compra_id'   => 'integer',
        'obra_id'              => 'integer',
        'codigo_insumo'        => 'string',
        'obs'                  => 'string',
        'justificativa'        => 'string',
        'tems'                 => 'string',
        'grupo_id'             => 'integer',
        'subgrupo1_id'         => 'integer',
        'subgrupo2_id'         => 'integer',
        'subgrupo3_id'         => 'integer',
        'servico_id'           => 'integer',
        'insumo_id'            => 'integer',
        'sugestao_data_uso'    => 'date',
        'sugestao_contrato_id' => 'integer',
        'user_id'              => 'integer',
        'unidade_sigla'        => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sugestao_data_uso' => 'date',
    ];

    public function getQtdAttribute($value)
    {
        if (strlen($value) == 4) {
            $value = '0'.$value;
        }

        return number_format($value, 2, ',', '.');
    }

    public function setQtdAttribute($value)
    {
        $pontos = [","];
        $value = str_replace('.', '', $value);
        $result = str_replace($pontos, ".", $value);

        $this->attributes['qtd'] = $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
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
    public function ordemDeCompra()
    {
        return $this->belongsTo(OrdemDeCompra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo1()
    {
        return $this->belongsTo(Grupo::class, 'subgrupo1_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo2()
    {
        return $this->belongsTo(Grupo::class, 'subgrupo2_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function subgrupo3()
    {
        return $this->belongsTo(Grupo::class, 'subgrupo3_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'sugestao_contrato_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'unidade_sigla');
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
    public function anexos()
    {
        return $this->hasMany(OrdemDeCompraItemAnexo::class);
    }

    public function codigoServico()
    {
        $grupos = [
            $this->grupo_id,
            $this->subgrupo1_id,
            $this->subgrupo2_id,
            $this->subgrupo3_id,
            $this->servico_id
        ];

        return implode('.', $grupos) . ' ' . $this->servico->nome;
    }

    public function reapropriacoes()
    {
        return $this->hasMany(
            ContratoItemApropriacao::class,
            'ordem_de_compra_item_id'
        );
    }

    public function aprovacoes()
    {
        return $this->morphMany(WorkflowAprovacao::class, 'aprovavel');
    }

    public function irmaosIds()
    {
        return $this->ordemDeCompra->itens()->pluck('ordem_de_compra_itens.id', 'ordem_de_compra_itens.id')->toArray();
    }

    public function paiEmAprovacao()
    {
        if ($this->ordemDeCompra->oc_status_id!=3) {
            $this->ordemDeCompra->update(['oc_status_id' => 3]);
            OrdemDeCompraStatusLog::create([
                'oc_status_id'=>$this->ordemDeCompra->oc_status_id,
                'ordem_de_compra_id'=>$this->ordemDeCompra->id,
                'user_id'=>Auth::id()
            ]);
        }
    }

    public function confereAprovacaoGeral()
    {
        $qtd_itens = $this->ordemDeCompra->itens()->count();
        $qtd_itens_aprovados = $this->ordemDeCompra->itens()->where('aprovado', '1')->count();
        $qtd_itens_sem_voto = $this->ordemDeCompra->itens()->whereNull('aprovado')->count();

        // Verifica se todos foram aprovados
        if ($qtd_itens === $qtd_itens_aprovados) {

            $this->ordemDeCompra->update(['oc_status_id' => 5,'aprovado' => 1]);

            OrdemDeCompraStatusLog::create([
                'oc_status_id'       => $this->ordemDeCompra->oc_status_id,
                'ordem_de_compra_id' => $this->ordemDeCompra->id,
                'user_id'            => Auth::id()
            ]);

            $itens_aditivos = $this->ordemDeCompra
                ->itens()
                ->whereNotNull('sugestao_contrato_id')
                ->get();

            if($itens_aditivos->count()) {
                app(QuadroDeConcorrenciaRepository::class)
                    ->aditivarContratos($itens_aditivos, $this->ordemDeCompra->user_id);
            }

            QuadroDeConcorrenciaRepository::verificaQCAutomatico();
        }

        // Verifica se algum foi reprovado e todos foram votados
        if ($qtd_itens !== $qtd_itens_aprovados && $qtd_itens_sem_voto===0) {
            $this->ordemDeCompra->update(['oc_status_id' => 4,'aprovado'=>0]);
            OrdemDeCompraStatusLog::create([
                'oc_status_id'=>$this->ordemDeCompra->oc_status_id,
                'ordem_de_compra_id'=>$this->ordemDeCompra->id,
                'user_id'=>Auth::id()
            ]);
        }
    }

    public function qualObra()
    {
        return $this->ordemDeCompra->obra_id;
    }

    public function aprova($valor)
    {
        $this->timestamps = false;
        $this->attributes['aprovado'] = $valor;
        $this->save();
    }
}
