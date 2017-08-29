<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrdemDeCompra
 * @package App\Models
 * @version April 4, 2017, 5:25 pm BRT
 */
class OrdemDeCompra extends Model
{
    use SoftDeletes;

    public $table = 'ordem_de_compras';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'oc_status_id',
        'obra_id',
        'user_id',
        'aprovado',
        'saldo_disponivel_temp'
    ];

    public static $workflow_tipo_id = WorkflowTipo::OC;

    public function workflowNotification()
    {
        return [
            'message' => 'OC '.$this->id.' à aprovar',
            'link' => route('ordens_de_compra.detalhes', $this->id),
            'workflow_tipo_id' => WorkflowTipo::OC,
            'id_dinamico' => $this->id,
            'task'=>1,
            'done'=>0
        ];
    }

    public function workflowNotificationDone($aprovado)
    {
        return [
            'message' => 'Ordem de compra '.$this->id.($aprovado?' aprovada ':' reprovada '),
            'link' => route('ordens_de_compra.detalhes', $this->id)
        ];
    }

    public function dataUltimoPeriodoAprovacao(){
        $ultimoStatusAprovacao = $this->ordemDeCompraStatusLogs()->where('oc_status_id',3)
            ->orderBy('created_at','DESC')->first();
        if($ultimoStatusAprovacao){
            return $ultimoStatusAprovacao->created_at;
        }
        return null;
    }

    public function qualObra(){
        return $this->obra_id;
    }

    public function irmaosIds()
    {
        return [$this->attributes['id'] => $this->attributes['id']];
    }

    public function idPai(){
        return null;
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'oc_status_id' => 'integer',
        'obra_id' => 'integer',
        'aprovado' => 'integer',
        'user_id' => 'integer',
        'saldo_disponivel_temp' => 'decimal',
    ];

    public static $filters = [
        'obra-foreign_key-Obra-nome-id' => 'Obra',
        'oc_status_id-foreign_key-OcStatus-nome-id' => 'Situação',
        'aprovado-boolean' => 'Aprovado',
        'ordem_compra_created_at-date' => 'Criado em',
        'ordem_compra_updated_at-date' => 'Atualizado em'
    ];

    public static $filters_obras_insumos = [
        'nome-string' => 'Nome insumo',
        'insumo_grupo_id-foreign_key-InsumoGrupo-nome-id' => 'Grupo de insumo'
    ];

    public static $filters_insumos = [
        'insumo_servico_servico_id-foreign_key-Servico-nome-id' => 'Servico'
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
    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function ocStatus()
    {
        return $this->belongsTo(OcStatus::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function itens()
    {
        return $this->hasMany(OrdemDeCompraItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ordemDeCompraStatusLogs()
    {
        return $this->hasMany(OrdemDeCompraStatusLog::class);
    }

    public function getNotificationData($param)
    {
        return null;
    }
}
