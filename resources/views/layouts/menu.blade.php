{{--<li class="active treeview">--}}
    {{--<a href="#">--}}
        {{--<i class="fa fa-dashboard"></i> <span>Dashboard</span>--}}
            {{--<span class="pull-right-container">--}}
              {{--<i class="fa fa-angle-left pull-right"></i>--}}
            {{--</span>--}}
    {{--</a>--}}
    {{--<ul class="treeview-menu">--}}
        {{--<li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>--}}
        {{--<li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>--}}
    {{--</ul>--}}
{{--</li>--}}

@if(Defender::canDo('users.list'))
    <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a href="{!! route('manage.index') !!}"><i class="fa fa-users"></i><span>Controle de Acesso</span></a>
    </li>
@endif

<li class="treeview {{ Request::is('admin/orcamento*') ? 'active' : '' }}">
    <a href="#">
        <i class="fa fa-balance-scale"></i> <span>Orçamento</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="{{ Request::is('admin/orcamentos*') ? 'active' : '' }}"><a href="{!! route('admin.orcamentos.index') !!}"><i class="fa fa-external-link-square"></i><span>Orçamentos</span></a></li>
        <li class="{{ Request::is('admin/orcamento') ? 'active' : '' }}"><a href="{!! route('admin.orcamentos.indexImport') !!}"><i class="glyphicon glyphicon-cloud-upload"></i><span>Importação</span></a></li>
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
        <li class="{{ Request::is('admin/planejamentos/planejamentoOrcamentos*') ? 'active' : '' }}"><a href="{!! route('admin.planejamentoOrcamentos.index') !!}"><i class="fa fa-exchange"></i><span>Planejamentos/Orcamentos</span></a></li>
        <li class="{{ Request::is('admin/planejamentoCronogramas*') ? 'active' : '' }}"><a href="{!! route('admin.planejamentoCronogramas.index') !!}"><i class="fa fa-calendar-plus-o"></i><span>Cronograma por obra</span></a></li>
        <li class="{{ Request::is('admin/planejamentos') ? 'active' : '' }}"><a href="{!! route('admin.planejamentos.index') !!}"><i class="fa fa-calendar-o"></i><span>Cronograma de obra</span></a></li>
        <li class="{{ Request::is('admin/planejamento') ? 'active' : '' }}"><a href="{!! route('admin.planejamentos.indexImport') !!}"><i class="glyphicon glyphicon-cloud-upload"></i><span>Importação</span></a></li>
        <li class="{{ Request::is('admin/planejamentos/lembretes*') ? 'active' : '' }}"><a href="{!! route('admin.lembretes.index') !!}"><i class="fa fa-exclamation-circle"></i><span>Lembretes</span></a></li>
        {{--<li class="{{ Request::is('admin/planejamentos/tipo-lembretes*') ? 'active' : '' }}">
            <a href="{!! route('admin.lembreteTipos.index') !!}"><i class="fa fa-ellipsis-h"></i><span>Tipos de Lembretes</span></a>
        </li>--}}
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
        <li class="{{ Request::is('admin/workflow/workflow-alcadas*') ? 'active' : '' }}"><a href="{!! route('admin.workflowAlcadas.index') !!}"><i class="fa fa-list-ol"></i><span>Alçadas</span></a></li>
        <li class="{{ Request::is('admin/workflow/reprovacao-motivos*') ? 'active' : '' }}"><a href="{!! route('admin.workflowReprovacaoMotivos.index') !!}"><i class="fa fa-ban"></i> Motivos de Reprovação</a></li>
    </ul>
</li>

<li class="{{ Request::is('admin/contratos*') ? 'active' : '' }}">
    <a href="{!! route('admin.catalogo_contratos.index') !!}"><i class="fa fa-file-text-o"></i><span>Contratos</span></a>
</li>

<li class="{{ Request::is('admin/obras*') ? 'active' : '' }}">
    <a href="{!! route('admin.obras.index') !!}"><i class="fa fa-building"></i><span>Obras</span></a>
</li>

<li class="{{ Request::is('insumos*') ? 'active' : '' }}">
    <a href="{!! route('admin.insumos.index') !!}"><i class="fa fa-gamepad"></i><span>Insumos</span></a>
</li>

<li class="{{ Request::is('insumoGrupos*') ? 'active' : '' }}">
    <a href="{!! route('admin.insumoGrupos.index') !!}"><i class="fa fa-th-list"></i><span>Grupos de insumos</span></a>
</li>

<li class="{{ Request::is('templatePlanilhas*') ? 'active' : '' }}">
    <a href="{!! route('admin.templatePlanilhas.index') !!}"><i class="fa fa-table"></i><span>Template de planilhas</span></a>
</li>

<li class="{{ Request::is('tipoEqualizacaoTecnicas*') ? 'active' : '' }}">
    <a href="{!! route('admin.tipoEqualizacaoTecnicas.index') !!}"><i class="fa fa-file-audio-o"></i><span>Equalização técnicas</span></a>
</li>

<li class="{{ Request::is('admin/retroalimentacaoObras*') ? 'active' : '' }}">
    <a href="{!! route('retroalimentacaoObras.index') !!}"><i class="fa fa-edit"></i><span>Retroalimentação de obras</span></a>
</li>

<li class="{{ Request::is('fornecedores*') ? 'active' : '' }}">
<a href="{!! route('admin.fornecedores.index') !!}"><i class="fa fa-user-plus"></i><span>Fornecedores</span></a>
</li>

