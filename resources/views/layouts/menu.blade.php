@if(Defender::canDo('users.list'))
    <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a href="{!! route('manage.index') !!}"><i class="fa fa-users"></i><span>Controle de Acesso</span></a>
    </li>
@endif

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

