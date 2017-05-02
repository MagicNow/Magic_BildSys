<li class="{{ Request::is('compras*') ? 'active' : '' }}">
    <a href="{!! url('compras') !!}"><i class="fa fa-shopping-cart "></i><span>Compras</span></a>
</li>


<li class="treeview">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Ordem de Compras</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('ordens-de-compra*') ? 'active' : '' }}">
            <a href="{!! route('ordens-de-compra.index') !!}"><i class="fa fa-shopping-basket"></i><span>Ordens de compra</span></a>
        </li>
        <li class="{{ Request::is('compras/dashboard') ? 'active' : '' }}"><a href="{{url('compras/dashboard')}}"><i class="glyphicon glyphicon-cloud-upload"></i><span>DashBoard</span></a></li>
    </ul>
</li>

<li class="{{ Request::is('quadroDeConcorrencias*') ? 'active' : '' }}">
    <a href="{!! route('quadroDeConcorrencias.index') !!}"><i class="fa fa-edit"></i><span>Quadro De Concorrência</span></a>
</li>