<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoServico
 * @package App\Models
 * @version July 11, 2017, 2:21 pm BRT
 */
class MedicaoServico extends Model
{
    public $table = 'medicao_servicos';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'qtd_funcionarios',
        'qtd_ajudantes',
        'qtd_outros',
        'descontos',
        'descricao_descontos',
        'periodo_inicio',
        'periodo_termino',
        'contrato_item_apropriacao_id',
        'user_id',
        'finalizado',
        'aprovado',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'qtd_funcionarios' => 'integer',
        'qtd_ajudantes' => 'integer',
        'qtd_outros' => 'integer',
        'descricao_descontos' => 'string',
        'periodo_inicio' => 'date',
        'periodo_termino' => 'date',
        'contrato_item_apropriacao_id' => 'integer',
        'user_id' => 'integer',
        'finalizado' => 'integer',
        'aprovado' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'periodo_inicio' => 'required',
        'periodo_termino' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function medicoes()
    {
        return $this->hasMany(Medicao::class);
    }

    public function contratoItemApropriacao(){
        return $this->belongsTo(ContratoItemApropriacao::class,'contrato_item_apropriacao_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getPeriodoInicioAttribute($value){
        if($value) {
            $date = new \DateTime($value);
            return $date->format('Y-m-d');
        }else{
            return $value;
        }
    }

    public function getPeriodoInicioBrAttribute($value){
        if($value) {
            $date = new Carbon($value);
            return $date->format('d/m/Y');
        }else{
            return $value;
        }
    }

    public function getPeriodoTerminoAttribute($value){
        if($value) {
            $date = new \DateTime($value);
            return $date->format('Y-m-d');
        }else{
            return $value;
        }
    }

    public function getPeriodoTerminoBrAttribute($value){
        if($value) {
            $date = new Carbon($value);
            return $date->format('d/m/Y');
        }else{
            return $value;
        }
    }
    
    // Aprovação

    public static $workflow_tipo_id = WorkflowTipo::MEDICAO;

    public function workflowNotification()
    {
        return [
            'message' => 'Você tem uma medição para aprovar',
            'link' => route('medicaoServicos.show', $this->id),
            'workflow_tipo_id' => WorkflowTipo::MEDICAO,
            'id_dinamico' => $this->id
        ];
    }

    public function irmaosIds()
    {
        return [$this->attributes['id'] => $this->attributes['id']];
    }
}
