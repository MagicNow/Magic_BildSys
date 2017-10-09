<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacaoEstoqueLocal extends Model
{
    public $table = 'aplicacao_estoque_locais';

    public $fillable = [
        'pavimento',
        'andar',
        'apartamento',
        'comodo',
    ];
}
