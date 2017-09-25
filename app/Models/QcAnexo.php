<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class QcAnexos
 * @package App\Models
 * @version Sep 24, 2017, 22:18 pm BRT
 */
class QcAnexo extends Model
{
	public $table = 'qc_anexos';

	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';


	public $fillable = [
		'qc_id',
		'arquivo',
		'tipo',
		'descricao'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'arquivo' => 'string',
		'tipo' => 'string',
		'descricao' => 'string',
		'qc_id' => 'integer'
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
	public function qc()
	{
		return $this->belongsTo(Qc::class);
	}
}
