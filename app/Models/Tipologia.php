<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Tipologia
 * @package App\Models
 * @version April 25, 2017, 2:16 pm BRT
 */
class Tipologia extends Model
{
	public $table = 'tipologias';

	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';


	protected $dates = ['deleted_at'];


	public $fillable = [
		'nome',
	];

	public static $campos = [
		'nome',        
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'nome' => 'string',        
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		'nome' => 'required'
	];
}
