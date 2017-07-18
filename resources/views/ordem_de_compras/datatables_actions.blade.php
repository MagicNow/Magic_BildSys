@if(strtolower($situacao) == 'reprovada')
    <button type="button" class="btn btn-ms btn-flat" onclick="reabrir({{$id}}, {{$obra_id}});">
        Reabrir
    </button>
@elseif(strtolower($situacao) == 'em aberto')
    <a href="ordens-de-compra/carrinho?id={{$id}}">
        <i class="fa fa-eye" aria-hidden="true" style="font-size: 25px;"></i>
    </a>
@else
    <a href="ordens-de-compra/detalhes/{{$id}}">
        <i class="fa fa-eye" aria-hidden="true" style="font-size: 25px;"></i>
    </a>
@endif