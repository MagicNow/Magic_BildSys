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

<li class="{{ Request::is('import*') ? 'active' : '' }}">
    <a href="{!! route('admin.import.index') !!}"><i class="fa fa-users"></i><span>Importação</span></a>
</li>
