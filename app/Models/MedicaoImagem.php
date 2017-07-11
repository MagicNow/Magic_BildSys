<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class MedicaoImagem
 * @package App\Models
 * @version July 11, 2017, 2:13 pm BRT
 */
class MedicaoImagem extends Model
{
    public $table = 'medicao_imagens';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'medicao_id',
        'imagem'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'di' => 'integer',
        'medicao_id' => 'integer',
        'imagem' => 'string'
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
    public function medicao()
    {
        return $this->belongsTo(Medicao::class);
    }
}
