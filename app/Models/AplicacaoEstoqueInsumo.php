<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacaoEstoqueInsumo extends Model
{
    public $table = 'aplicacao_estoque_insumos';

    public $fillable = [
        'requisicao_id',
        'aplicacao_estoque_local_id',
        'obra_id',
        'insumo_id',
        'qtd',
        'unidade_medida',
        'pavimento',
        'andar',
        'apartamento',
        'comodo',
    ];
}
