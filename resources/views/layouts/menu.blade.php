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

<li class="{{ Request::is('orcamento*') ? 'active' : '' }}">
    <a href="{!! route('admin.orcamento.index') !!}"><i class="glyphicon glyphicon-cloud-upload"></i><span>Importação</span></a>
</li>

<li class="active treeview">
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

<li class="{{ Request::is('contratos*') ? 'active' : '' }}">
    <a href="{!! route('admin.contratos.index') !!}"><i class="fa fa-edit"></i><span>Contratos</span></a>
</li>

