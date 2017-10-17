<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Carteira
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class QcAvulsoCarteira extends Model
{
    public $table = 'qc_avulso_carteiras';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nome',
        'sla_start',
        'sla_negociacao',
        'sla_mobilizacao',
        'user_id'
    ];

    public static $campos = [
        'nome',
        'sla_start',
        'sla_negociacao',
        'sla_mobilizacao',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'nome' => 'string',
        'sla_start' => 'integer',
        'sla_negociacao' => 'integer',
        'sla_mobilizacao' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nome' => 'required|unique:qc_avulso_carteiras,nome',
        'sla_start' => 'required',
        'sla_negociacao' => 'required',
        'sla_mobilizacao' => 'required',
        'users' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function users()
    {
        return $this->belongsToMany(User::class, 'qc_avulso_carteira_users', 'qc_avulso_carteira_id', 'user_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tarefas()
    {
        return $this->belongsToMany(Planejamento::class, 'qc_avulso_carteira_planejamento', 'qc_avulso_carteira_id', 'planejamento_id')
            ->withPivot('deleted_at')
            ->withTimestamps();
    }
}
