<li class="treeview {{ Request::is('obras*')||
					   Request::is('carteiras*')||
                       Request::is('insumos*') ||
                       Request::is('insumoGrupos*') ||
                       Request::is('solicitacaoInsumos*') ||
                       Request::is('fornecedores*') ||
                       Request::is('condicoes-de-pagamento*') ||
                       Request::is('tipos-de-documentos-fiscais*') ||
                       Request::is('tipos-de-documentos-financeiros*')
                       ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-pencil-square-o"></i>
        <span>Cadastros</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('fornecedores.list')
        <li class="{{ Request::is('fornecedores*') ? 'active' : '' }}">
            <a href="{!! route('admin.fornecedores.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Fornecedores</span>
            </a>
        </li>
        @endshield
		
		@shield('carteiras.list')
        <li class="{{ Request::is('carteiras*') ? 'active' : '' }}">
            <a href="{!! route('admin.carteiras.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Carteiras</span>
            </a>
        </li>
        @endshield		

        @shield('grupos_insumos.list')
        <li class="{{ Request::is('insumoGrupos*') ? 'active' : '' }}">
            <a href="{!! route('admin.insumoGrupos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Grupos de insumos</span></a>
        </li>
        @endshield

        @shield('insumos.list')
        <li class="{{ Request::is('insumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.insumos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Insumos</span>
            </a>
        </li>
        @endshield

        @shield('obras.list')
        <li class="{{ Request::is('obras*') ? 'active' : '' }}">
            <a href="{!! route('admin.obras.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Obras</span>
            </a>
        </li>
        @endshield

        @shield('padraoEmpreendimentos.list')
        <li class="{{ Request::is('padraoEmpreendimentos*') ? 'active' : '' }}">
            <a href="{!! route('padraoEmpreendimentos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Padrões de empreendimento</span>
            </a>
        </li>
        @endshield

        @shield('padraoEmpreendimentos.list')
        <li class="{{ Request::is('regionals*') ? 'active' : '' }}">
            <a href="{!! route('regionals.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Regionais</span>
            </a>
        </li>
        @endshield

        @shield('solicitacaoInsumos.list')
        <li class="{{ Request::is('solicitacaoInsumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.solicitacaoInsumos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Solicitações de insumos</span>
            </a>
        </li>
        @endshield

        @shield('qc.list')
        <li class="{{ Request::is('qc*') ? 'active' : '' }}">
            <a href="{{ route('qc.index') }}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Q.C. Avulso</span>
            </a>
        </li>
        @endshield


        <li class="{{ Request::is('condicoes-de-pagamento*') ? 'active' : '' }}">
            <a href="{!! route('pagamentoCondicaos.index') !!}"><i class="fa fa-caret-right"></i><span>Condições de Pagamento</span></a>
        </li>

        <li class="{{ Request::is('tipos-de-documentos-fiscais*') ? 'active' : '' }}">
            <a href="{!! route('documentoTipos.index') !!}"><i class="fa fa-caret-right"></i><span> Tipos de Doc. Fiscais</span></a>
        </li>

        <li class="{{ Request::is('tipos-de-documentos-financeiros*') ? 'active' : '' }}">
            <a href="{!! route('documentoFinanceiroTipos.index') !!}"><i class="fa fa-caret-right"></i><span> Tipos de Doc. Financeiros</span></a>
        </li>
    </ul>
</li>

@shield('ordens_de_compra.list')
<li class="treeview {{ Request::is('ordens-de-compra*')||Request::is('compras/dashboard')||Request::is('compras*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-shopping-cart"></i>
        <span>Compras</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('compras.geral')
        <li class="{{ Request::is('compras*') ? 'active' : '' }}">
            <a href="{!! url('compras') !!}"><i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Calendário de Compras</span>
            </a>
        </li>
        @endshield

        <li class="{{ Request::is('compras/dashboard') ? 'active' : '' }}">
            <a href="{{url('compras/dashboard')}}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>DashBoard de compra</span>
            </a>
        </li>

        <li class="{{ Request::is('ordens-de-compra*') ? 'active' : '' }}">
            <a href="{!! route('ordens-de-compra.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Ordens de compra</span></a>
        </li>
    </ul>
</li>
@endshield

<li class="treeview {{ Request::is('medicoes*')||
                        Request::is('boletim-medicao*')||
                        Request::is('contratos*')||
                        Request::is('notafiscals*') ||
                       Request::is('nomeclaturaMapas*') ||
                       Request::is('memoriaCalculos*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Contratos</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">

        <li class="{{ Request::is('boletim-medicao*') ? 'active' : '' }}">
            <a href="{!! route('boletim-medicao.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Boletins de Medição</span></a>
        </li>

        @shield('contratos.list')
        <li class="{{ Request::is('contratos*') ? 'active' : '' }}">
            <a href="{!! route('contratos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Contratos</span>
            </a>
        </li>
        @endshield

        <li class="{{ Request::is('pagamentos*') ? 'active' : '' }}">
            <a href="{!! route('pagamentos.index') !!}"><i class="fa fa-caret-right"></i><span>Pagamentos</span></a>
        </li>

        <li class="{{ Request::is('medicoes*') ? 'active' : '' }}">
            <a href="{!! route('medicoes.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Lista de Medicões</span></a>
        </li>
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

        @shield('memoriaCalculos.list')
        <li class="{{ Request::is('memoriaCalculos*') ? 'active' : '' }}">
            <a href="{!! route('memoriaCalculos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Memória de Cálculo</span>
            </a>
        </li>
        @endshield

        @shield('nomeclaturaMapas.list')
        <li class="{{ Request::is('nomeclaturaMapas*') ? 'active' : '' }}">
            <a href="{!! route('admin.nomeclaturaMapas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Nomeclatura Mem. Cálc.</span>
            </a>
        </li>
        @endshield

    </ul>
</li>

@shield('retroalimentacao.list')
<li class="{{ Request::is('retroalimentacaoObras*') ? 'active' : '' }}">
    <a href="{!! route('retroalimentacaoObras.index') !!}">
        <i class="fa fa-retweet" aria-hidden="true"></i>
        <span>Retroalimentação de obras</span></a>
</li>
@endshield

<li class="treeview {{ Request::is('quadro-de-concorrencia*')||Request::is('catalogo-acordos*')||Request::is('tipoEqualizacaoTecnicas*') ||Request::is('lpu*')? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-diamond" aria-hidden="true"></i> <span>Suprimentos</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('catalogo_acordos.list')
        <li class="{{ Request::is('catalogo-acordos*') ? 'active' : '' }}">
            <a href="{!! route('catalogo_contratos.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Catálogo de Acordos</span></a>
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
        <li class="{{ Request::is('tipoEqualizacaoTecnicas*') ? 'active' : '' }}">
            <a href="{!! route('tipoEqualizacaoTecnicas.index') !!}">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Equalização técnicas</span></a>
        </li>
        @endshield

        {{--@shield('lista_qc.list')--}}
        {{--<li class="{{ Request::is('lista-qc*') ? 'active' : '' }}">--}}
            {{--<a href="{!! route('listaQc.index') !!}" title="Quadro De Concorrência">--}}
                {{--<i class="fa fa-caret-right" aria-hidden="true"></i>--}}
                {{--<span>Lista de Q.C.</span> </a>--}}
        {{--</li>--}}
        {{--@endshield--}}

        @shield('quadroDeConcorrencias.list')
        <li class="{{ Request::is('quadro-de-concorrencia*') ? 'active' : '' }}">
            <a href="{!! route('quadroDeConcorrencias.index') !!}" title="Quadro De Concorrência">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Q.C.</span> </a>
        </li>
        @endshield
		
		@shield('lpu.list')
        <li class="{{ Request::is('lpu*') ? 'active' : '' }}">
            <a href="{!! route('lpu.index') !!}" title="LPU">
                <i class="fa fa-caret-right" aria-hidden="true"></i>
                <span>Lista de Preço Unitário</span> </a>
        </li>
        @endshield		

    </ul>
</li>
