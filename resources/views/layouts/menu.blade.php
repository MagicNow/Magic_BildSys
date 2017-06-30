@shield('users.list')
    <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a href="{!! route('manage.index') !!}"><i class="fa fa-users"></i><span>Controle de Acesso</span></a>
    </li>
@endshield

<li class="treeview {{ Request::is('admin/orcamento*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Orçamento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('orcamentos.list')
            <li class="{{ Request::is('admin/orcamentos*') ? 'active' : '' }}"><a href="{!! route('admin.orcamentos.index') !!}"><i class="fa fa-external-link-square"></i><span>Orçamentos</span></a></li>
        @endshield
        @shield('orcamentos.import')
            <li class="{{ Request::is('admin/orcamento') ? 'active' : '' }}"><a href="{!! route('admin.orcamentos.indexImport') !!}"><i class="glyphicon glyphicon-cloud-upload"></i><span>Importação</span></a></li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/planejamento*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-calendar"></i> <span>Cronograma de Obras</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/planejamentos/planejamentoOrcamentos*') ? 'active' : '' }}"><a href="{!! route('admin.planejamentoOrcamentos.index') !!}"><i class="fa fa-exchange"></i><span>Tarefa/Orcamentos</span></a></li>
        @shield('cronograma_por_obras.list')
            <li class="{{ Request::is('admin/planejamentoCronogramas*') ? 'active' : '' }}"><a href="{!! route('admin.planejamentoCronogramas.index') !!}"><i class="fa fa-calendar-plus-o"></i><span>Cronograma por obra</span></a></li>
        @endshield
        @shield('cronograma_de_obras.list')
            <li class="{{ Request::is('admin/planejamentos') ? 'active' : '' }}"><a href="{!! route('admin.planejamentos.index') !!}"><i class="fa fa-calendar-o"></i><span>Cronograma de obra</span></a></li>
        @endshield
        @shield('planejamento.import')
            <li class="{{ Request::is('admin/planejamento') ? 'active' : '' }}"><a href="{!! route('admin.planejamentos.indexImport') !!}"><i class="glyphicon glyphicon-cloud-upload"></i><span>Importação</span></a></li>
        @endshield
        @shield('lembretes.list')
            <li class="{{ Request::is('admin/planejamentos/lembretes*') ? 'active' : '' }}"><a href="{!! route('admin.lembretes.index') !!}"><i class="fa fa-exclamation-circle"></i><span>Lembretes</span></a></li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/workflow*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-check-square"></i> <span>Workflow</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('alcadas.list')
            <li class="{{ Request::is('admin/workflow/workflow-alcadas*') ? 'active' : '' }}"><a href="{!! route('admin.workflowAlcadas.index') !!}"><i class="fa fa-list-ol"></i><span>Alçadas</span></a></li>
        @endshield
        @shield('motivos_reprovacao.list')
            <li class="{{ Request::is('admin/workflow/reprovacao-motivos*') ? 'active' : '' }}"><a href="{!! route('admin.workflowReprovacaoMotivos.index') !!}"><i class="fa fa-ban"></i> Motivos de Reprovação</a></li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/obras*') ||
                       Request::is('admin/retroalimentacaoObras*')
                       ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-building"></i> <span>Obra</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('obras.list')
        <li class="{{ Request::is('admin/obras*') ? 'active' : '' }}">
            <a href="{!! route('admin.obras.index') !!}"><i class="fa fa-building"></i><span>Obras</span></a>
        </li>
        @endshield

        @shield('retroalimentacao.list')
        <li class="{{ Request::is('admin/retroalimentacaoObras*') ? 'active' : '' }}">
            <a href="{!! route('retroalimentacaoObras.index') !!}"><i class="fa fa-magic"></i><span>Retroalimentação de obras</span></a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/insumos*') ||
                       Request::is('admin/insumoGrupos*') ||
                       Request::is('admin/compradorInsumos*') ||
                       Request::is('admin/solicitacaoInsumos*')
                       ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-gamepad"></i> <span>Insumo</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('insumos.list')
        <li class="{{ Request::is('admin/insumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.insumos.index') !!}"><i class="fa fa-gamepad"></i><span>Insumos</span></a>
        </li>
        @endshield
        @shield('grupos_insumos.list')
        <li class="{{ Request::is('admin/insumoGrupos*') ? 'active' : '' }}">
            <a href="{!! route('admin.insumoGrupos.index') !!}"><i class="fa fa-th-list"></i><span>Grupos de insumos</span></a>
        </li>
        @endshield
        @shield('compradorInsumos.list')
        <li class="{{ Request::is('admin/compradorInsumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.compradorInsumos.index') !!}"><i class="fa fa-child"></i><span>Comprador/Insumos</span></a>
        </li>
        @endshield
        @shield('solicitacaoInsumos.list')
        <li class="{{ Request::is('admin/solicitacaoInsumos*') ? 'active' : '' }}">
            <a href="{!! route('admin.solicitacaoInsumos.index') !!}"><i class="fa fa-edit"></i><span>Solicitações de insumos</span></a>
        </li>
        @endshield
    </ul>
</li>

<li class="treeview {{ Request::is('admin/templatePlanilhas*') ||
                       Request::is('admin/contratoTemplates*')
                       ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-table"></i> <span>Templates</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        @shield('template_planilhas.list')
        <li class="{{ Request::is('admin/templatePlanilhas*') ? 'active' : '' }}">
            <a href="{!! route('admin.templatePlanilhas.index') !!}"><i class="fa fa-table"></i><span>Template de planilhas</span></a>
        </li>
        @endshield
        @shield('contratoTemplates.list')
        <li class="{{ Request::is('admin/contratoTemplates*') ? 'active' : '' }}">
            <a href="{!! route('admin.contratoTemplates.index') !!}"><i class="fa fa-file-text-o"></i><span>Templates de Contratos</span></a>
        </li>
        @endshield
    </ul>
</li>

@shield('fornecedores.list')
    <li class="{{ Request::is('admin/fornecedores*') ? 'active' : '' }}">
    <a href="{!! route('admin.fornecedores.index') !!}"><i class="fa fa-user-plus"></i><span>Fornecedores</span></a>
    </li>
@endshield

<li class="treeview {{ Request::is('admin/nomeclaturaMapas*') || Request::is('memoriaCalculos*')
                       ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-table"></i> <span>Memória de Cálculo</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/nomeclaturaMapas*') ? 'active' : '' }}">
            <a href="{!! route('admin.nomeclaturaMapas.index') !!}"><i class="fa fa-map-o"></i><span>Nomeclaturas</span></a>
        </li>
        <li class="{{ Request::is('memoriaCalculos*') ? 'active' : '' }}">
            <a href="{!! route('memoriaCalculos.index') !!}"><i class="fa fa-building-o"></i><span>Memoria de Calculo</span></a>
        </li>
    </ul>
</li>


