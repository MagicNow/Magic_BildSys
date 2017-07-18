@if(strtolower($situacao) == 'reprovada')
    <button type="button" class="btn btn-ms btn-flat" onclick="reabrir({{$id}}, {{$obra_id}});">
        Reabrir
    </button>
@elseif(strtolower($situacao) == 'em aberto')
    <a href="ordens-de-compra/carrinho?id={{$id}}">
        <button type="button" class="btn btn-ms btn-flat">
            <i class="fa fa-eye" aria-hidden="true" style="font-size: 17px;"></i>
        </button>
    </a>
@else
    <a href="ordens-de-compra/detalhes/{{$id}}">
        <button type="button" class="btn btn-ms btn-flat">
            <i class="fa fa-eye" aria-hidden="true" style="font-size: 17px;"></i>
        </button>
    </a>
@endif