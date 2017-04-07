<li class="active treeview">
    <a href="#">
        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('ordemDeCompras*') ? 'active' : '' }}">
            <a href="{!! route('ordemDeCompras.index') !!}">
                <i class="fa fa-list-alt"></i> Listagem de OC
            </a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('compras*') ? 'active' : '' }}">
    <a href="{!! url('compras') !!}"><i class="fa fa-shopping-cart "></i><span>Compras</span></a>
</li>


