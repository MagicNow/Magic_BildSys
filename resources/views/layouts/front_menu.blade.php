<li class="{{ Request::is('compras*') ? 'active' : '' }}">
    <a href="{!! url('compras') !!}"><i class="fa fa-shopping-cart "></i><span>Compras</span></a>
</li>
<li class="{{ Request::is('ordens-de-compra*') ? 'active' : '' }}">
    <a href="{!! route('ordens-de-compra.index') !!}"><i class="fa fa-shopping-basket"></i><span>OrdemDeCompras</span></a>
</li>

<li class="{{ Request::is('retroalimentacaoObras*') ? 'active' : '' }}">
    <a href="{!! route('retroalimentacaoObras.create') !!}"><i class="fa fa-undo"></i><span>Retroalimentação</span></a>
</li>