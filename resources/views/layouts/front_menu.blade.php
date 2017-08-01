


@shield('ordens_de_compra.list')
<li class="treeview {{ Request::is('ordens-de-compra*')||Request::is('compras/dashboard')||Request::is('compras*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-shopping-cart"></i> <span>Compras</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('compras.geral')
        <li class="{{ Request::is('compras*') ? 'active' : '' }}">
            <a href="{!! url('compras') !!}"><i class="fa fa-caret-right" aria-hidden="true"></i><span>Calendário de Compras</span></a>
        </li>
        @endshield
        <li class="{{ Request::is('ordens-de-compra*') ? 'active' : '' }}">
            <a href="{!! route('ordens-de-compra.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Ordens de compra</span></a>
        </li>

        <li class="{{ Request::is('compras/dashboard') ? 'active' : '' }}"><a href="{{url('compras/dashboard')}}"><i
                        class="fa fa-caret-right" aria-hidden="true"></i><span>DashBoard de compra</span></a></li>
    </ul>
</li>
@endshield
<li class="treeview {{ Request::is('quadro-de-concorrencia*')||Request::is('catalogo-acordos*')||Request::is('tipoEqualizacaoTecnicas*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-diamond" aria-hidden="true"></i> <span>Suprimentos</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('quadroDeConcorrencias.list')
        <li class="{{ Request::is('quadro-de-concorrencia*') ? 'active' : '' }}">
            <a href="{!! route('quadroDeConcorrencias.index') !!}" title="Quadro De Concorrência">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Q.C.</span> </a>
        </li>
        @endshield
        @shield('quadroDeConcorrencias.dashboard')
        <li class="{{ Request::is('quadro-de-concorrencia*') ? 'active' : '' }}">
            <a href="{!! route('quadroDeConcorrencias.dashboard') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Dashboard Q.C.</span></a>
        </li>
        @endshield
        @shield('catalogo_acordos.list')
        <li class="{{ Request::is('catalogo-acordos*') ? 'active' : '' }}">
            <a href="{!! route('catalogo_contratos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Catálogo de Acordos</span></a>
        </li>
        @endshield
        @shield('catalogo_acordos.list')
        <li class="{{ Request::is('tipoEqualizacaoTecnicas*') ? 'active' : '' }}">
            <a href="{!! route('tipoEqualizacaoTecnicas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Equalização técnicas</span></a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('medicoes*')|| Request::is('boletim-medicao*')|| Request::is('contratos*')|| Request::is('notafiscals*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Contratos</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">

        @shield('contratos.list')
        <li class="{{ Request::is('contratos*') ? 'active' : '' }}">
            <a href="{!! route('contratos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Contratos</span>
            </a>
        </li>
        @endshield
        <li class="{{ Request::is('notafiscals*') ? 'active' : '' }}">
            <a href="{!! route('notafiscals.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Nota fiscal</span></a>
        </li>
        <li class="{{ Request::is('medicoes*') ? 'active' : '' }}">
            <a href="{!! route('medicoes.preCreate') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Nova Medicão</span></a>
        </li>
        <li class="{{ Request::is('medicoes*') ? 'active' : '' }}">
            <a href="{!! route('medicoes.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Lista de Medicões</span></a>
        </li>
        <li class="{{ Request::is('boletim-medicao*') ? 'active' : '' }}">
            <a href="{!! route('boletim-medicao.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Boletins de Medição</span></a>
        </li>
    </ul>
</li>