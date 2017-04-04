
<li class="{{ Request::is('compras*') ? 'active' : '' }}">
    <a href="{!! url('compras') !!}"><i class="fa fa-shopping-cart "></i><span>Compras</span></a>
</li>
<li class="{{ Request::is('ordemDeCompras*') ? 'active' : '' }}">
    <a href="{!! route('ordemDeCompras.index') !!}"><i class="fa fa-edit"></i><span>OrdemDeCompras</span></a>
</li>