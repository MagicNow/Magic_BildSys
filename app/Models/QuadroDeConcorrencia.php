<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuadroDeConcorrencia extends Model
{
    public $table = 'quadro_de_concorrencias';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'user_id',
        'qc_status_id',
        'obrigacoes_fornecedor',
        'obrigacoes_bild',
        'rodada_atual'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'qc_status_id' => 'integer',
        'obrigacoes_fornecedor' => 'string',
        'obrigacoes_bild' => 'string',
        'rodada_atual' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [ ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tipoEqualizacaoTecnicas()
    {
        return $this->belongsToMany(
            TipoEqualizacaoTecnica::class,
            'qc_tipo_equalizacao_tecnica',
            'quadro_de_concorrencia_id',
            'tipo_equalizacao_tecnica_id'
        )
        ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itens()
    {
        return $this->hasMany(
            QuadroDeConcorrenciaItem::class,
            'quadro_de_concorrencia_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equalizacoesExtras()
    {
        return $this->hasMany(
            QcEqualizacaoTecnicaExtra::class,
            'quadro_de_concorrencia_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function anexos()
    {
        return $this->hasMany(
            QcEqualizacaoTecnicaAnexoExtra::class,
            'quadro_de_concorrencia_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function status()
    {
        return $this->belongsTo(QcStatus::class, 'qc_status_id');
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
    public function equalizacaoTecnicaAnexoExtras()
    {
        return $this->hasMany(QcEqualizacaoTecnicaAnexoExtra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function equalizacaoTecnicaExtras()
    {
        return $this->hasMany(QcEqualizacaoTecnicaExtra::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcFornecedores()
    {
        return $this->hasMany(QcFornecedor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function logs()
    {
        return $this->hasMany(QcStatusLog::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function qcTipoEqualizacaoTecnicas()
    {
        return $this->hasMany(QcTipoEqualizacaoTecnica::class);
    }

    // Funções da aprovação

    /**
     * Tipo de Workflow, necessário para models que são aprováveis
     *
     * @var integer
     */
    public static $workflow_tipo_id = 2; // Tipo = Workflow Validação de Escopo Q.C.

    public function aprovacoes(){
        return $this->morphMany(WorkflowAprovacao::class, 'aprovavel');
    }

    public function irmaosIds(){
        return [$this->attributes['id'] => $this->attributes['id']];
    }

    public function paiEmAprovacao(){
        return false;
    }

    public function confereAprovacaoGeral(){
        return false;
    }

    public function qualObra(){
        return null;
    }

    public function aprova($valor){
        if($valor){
            $qc_status_id = 5;
        }else{
            $qc_status_id = 4;
        }
        $this->attributes['qc_status_id'] = $qc_status_id;
        $this->save();

        QcStatusLog::create([
            'quadro_de_concorrencia_id' => $this->attributes['id'],
            'qc_status_id' => $this->attributes['qc_status_id'],
            'user_id' => $this->attributes['user_id']
        ]);
    }

    public function temOfertas()
    {
        return !$this->itens->pluck('ofertas')->flatten()->isEmpty();
    }

    public function hasServico()
    {
        return $this->itens
            ->pluck('insumo')
            ->pluck('insumoGrupo')
            ->pluck('nome')
            ->contains(function($nome) {
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

    public function contratos()
    {
        return $this->hasMany(Contrato::class);    
    }
}
