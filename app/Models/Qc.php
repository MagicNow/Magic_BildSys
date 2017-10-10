<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qc extends Model
{
	public $table = 'qc';

	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	public $fillable = [
		'obra_id',
		'tipologia_id',
		'carteira_id',
		'fornecedor_id',
		'comprador_id',
		'descricao',
		'valor_pre_orcamento',
		'valor_orcamento_inicial',
		'valor_gerencial',
		'valor_fechamento',
		'data_fechamento',
		'user_id',
		'numero_contrato',
        'qc_status_id'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'obra_id' => 'integer',
		'tipologia_id' => 'integer',
		'carteira_id' => 'integer',
		'fornecedor_id' => 'integer',
		'comprador_id' => 'integer',
		'descricao' => 'string',
		'valor_pre_orcamento' => 'float',
		'valor_orcamento_inicial' => 'float',
		'valor_fechamento' => 'float',
		'observacao' => 'string',
		'numero_contrato' => 'string',
		'status' => 'string',
        'deleted_at' => 'datetime',
        'data_fechamento' => 'datetime',
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'obra_id' => 'required|integer',
		'tipologia_id' => 'required|integer',
		'carteira_id' => 'required|integer',
		'comprador_id' => 'integer',
		'user_id' => 'integer',
		'fornecedor_id' => 'integer',
		'descricao' => 'required',
		'valor_pre_orcamento' => 'required',
		'valor_orcamento_inicial' => 'required',
		'valor_gerencial' => 'required'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rulesUpdate = [

	];

	public function status()
	{
		return $this->belongsTo(\App\Models\QcStatus::class, 'qc_status_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 **/
	public function obra()
	{
		return $this->belongsTo(\App\Models\Obra::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 **/
	public function carteira()
	{
		return $this->belongsTo(\App\Models\Carteira::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 **/
	public function tipologia()
	{
		return $this->belongsTo(\App\Models\Tipologia::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 **/
	public function anexos()
	{
		return $this->hasMany(QcAnexo::class, 'qc_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 **/
	public function comprador()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
