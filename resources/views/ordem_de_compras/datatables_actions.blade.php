@if(strtolower($situacao) == 'reprovada')
    <a href="ordens-de-compra/reabrir-ordem-de-compra/{{$id}}">
        <button type="button" class="btn btn-ms btn-flat">
            <i class="fa fa-eye" aria-hidden="true"></i> Reabrir
        </button>
    </a>
@elseif(strtolower($situacao) == 'em aberto')
    <a href="ordens-de-compra/carrinho?id={{$id}}">
        <button type="button" class="btn btn-ms btn-flat">
            <i class="fa fa-eye" aria-hidden="true"></i>
        </button>
    </a>
@else
    <a href="ordens-de-compra/detalhes/{{$id}}">
        <button type="button" class="btn btn-ms btn-flat">
            <i class="fa fa-eye" aria-hidden="true"></i>
        </button>
    </a>
@endif