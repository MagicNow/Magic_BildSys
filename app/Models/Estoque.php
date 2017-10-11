<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    public $table = 'estoque';

    public $fillable = [
        'obra_id',
        'insumo_id',
        'qtde',
    ];
}
