@extends('layouts.app')

@section('content')
    <div class="content">



        <div id="user-details">
            <!-- User details -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-user"></span>
                                Usuário:
                                {{ $user->name }}
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li>
                                    <strong>Email:</strong>
                                    {{ $user->email }}
                                </li>
                                <li>
                                    <strong>Status:</strong>
                                    {!! $user->active ? '<label class="label label-success">Ativo</label>' : '<label class="label label-danger">Inativo</label>' !!}
                                </li>
                                <li>
                                    <strong>Criado em:</strong>
                                    {{ $user->created_at->format("d/m/Y H:i:s") }}
                                </li>
                                <li>
                                    <strong>Última Atualização:</strong>
                                    {{ $user->updated_at->format("d/m/Y H:i:s") }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--/User details -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-align-justify"></span>
                                Papéis
                                <button data-toggle="modal" data-target='#role-modal-{{ $user->id }}' class="btn btn-success btn-xs pull-right">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    Adicionar Papéis
                                </button>
                            </h3>
                        </div>
                        <div class="panel-body">
                            @if(count($user->roles) > 0)
                                @foreach ($user->roles as $role)
                                    {!! Form::open(['route' => ['manage.users.remove.roles', $user->id, $role->id], 'id'=>'formDeleteRole'.$user->id . '-'. $role->id,  'method' => 'delete', 'class' => 'label-form' ]) !!}

                                    <div class="btn-group">
                                        <a href="{{ url('admin/manage/roles/' . $role->id . '/edit') }}}}" class="btn btn-info btn-xs">
                                            {{ $role->name }}
                                        </a>

                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> ', [
                                          'type' => 'button',
                                          'class' => 'btn btn-danger btn-xs',
                                          'onclick' => "confirmDelete('formDeleteRole".$user->id . '-'. $role->id."');",
                                          'title' => ucfirst(trans('common.delete'))
                                        ]) !!}
                                    </div>

                                    {!! Form::close() !!}
                                @endforeach
                            @else
                                <div class="text-danger text-center">
                                    <p><strong>Usuário não possui papéis cadastrados.</strong></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <span class="glyphicon glyphicon-lock"></span>
                                Permissões

                                <button data-toggle="modal" data-target='#permission-modal-{{ $user->id }}' class="btn btn-success btn-xs pull-right">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    Adicionar Permissões
                                </button>
                            </h3>
                        </div>
                        <div class="panel-body">
                            @if (count($user->permissions) > 0)
                                @foreach ($user->permissions as $permission)
                                    {!! Form::open(['route' => ['manage.users.remove.permissions', $user->id, $permission->id], 'id'=>'formDeletePermission'.$user->id . '-'. $permission->id,  'method' => 'delete', 'class' => 'label-form']) !!}

                                    <div class="btn-group">

                                        @if($permission->pivot->value == 1)
                                            <a href="{{ url('admin/manage/permissions/' . $permission->id . '/edit') }}}}" class="btn btn-info btn-xs">
                                                <i class="glyphicon glyphicon-ok-sign"></i> {{ $permission->readable_name }}
                                            </a>
                                        @else
                                            <a href="{{ url('admin/manage/permissions/' . $permission->id . '/edit') }}}}" class="btn btn-danger btn-xs">
                                                <i class="glyphicon glyphicon-lock"></i> {{ $permission->readable_name }}
                                            </a>
                                        @endif

                                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i> ', [
                                          'type' => 'button',
                                          'class' => 'btn btn-danger btn-xs',
                                          'onclick' => "confirmDelete('formDeletePermission".$user->id . '-'. $permission->id."');",
                                          'title' => ucfirst(trans('common.delete'))
                                        ]) !!}
                                    </div>

                                    {!! Form::close() !!}
                                @endforeach
                            @else
                                <div class="text-danger text-center">
                                    <p>
                                        <strong>Não há registro de permissões associadas a este usuário.</strong>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pull-right">
                @if($user->active)
                    {!! Form::open(['route' => ['manage.users.deactivate', $user->id], 'id'=>'formDeactivate'.$user->id,  'method' => 'patch']) !!}
                @else
                    {!! Form::open(['route' => ['manage.users.activate', $user->id], 'id'=>'formActivate'.$user->id,  'method' => 'patch']) !!}
                @endif

                <div class='btn-group'>
                    <a href="{{ url('admin/manage/users/' . $user->id . '/edit')  }}" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil"></span>
                        Editar
                    </a>

                    @if(Defender::canDo('users.deactivate'))
                        @if($user->active)
                            {!! Form::button('<i class="glyphicon glyphicon-remove"></i> ' . ucfirst(trans('common.deactivate')), [
                                              'type' => 'button',
                                              'class' => 'btn btn-danger',
                                              'onclick' => "confirmDeactivate('formDeactivate".$user->id."');",
                                              'title' => ucfirst(trans('common.deactivate'))
                            ]) !!}
                        @else
                            {!! Form::button('<i class="glyphicon glyphicon-ok"></i> ' . ucfirst(trans('common.activate')), [
                                              'type' => 'button',
                                              'class' => 'btn btn-success',
                                              'onclick' => "confirmActivate('formActivate".$user->id."');",
                                              'title' => ucfirst(trans('common.activate'))
                            ]) !!}
                        @endif
                    @endif
                </div>
                {!! Form::close() !!}
            </div>
            <!--/Actions -->
        </div>
    </div>


    <div class="modal fade" id="role-modal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="roleFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="roleFormLabel">Adicionar Papel</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Selecione o Papel que deseja adicionar.
                    </div>
                    <div class="clearfix"></div>
                    <div class="alert alert-danger hidden" id="role-form-errors"></div>

                    {{ Form::open(['url' => url("admin/manage/users/" . $user->id . "/roles/add") ,'method' => 'post', 'id' => 'role-form-' .  $user->id, 'role' => 'form']) }}

                    <div class="form-group">
                        <label class="col-md-3 control-label">Papel*</label>
                        <div class="col-md-9">
                            {{ Form::select('roles', $roles, null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    {{ Form::submit('Adicionar Papel', ['class' => 'btn btn-primary', 'id' => 'role-save-button']) }}
                    <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                    {{ Form::close() }}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="permission-modal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="permissionFormLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="permissionFormLabel">Adicionar Permissão</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Selecione a Permissão que deseja adicionar.
                    </div>
                    <div class="clearfix"></div>
                    <div class="alert alert-danger hidden" id="permission-form-errors"></div>

                    {{ Form::open(['url' => url("admin/manage/users/" . $user->id . "/permissions/add"), 'method' => 'post', 'id' => 'permission-form-' .  $user->id, 'role' => 'form']) }}

                    <div class="form-group clearfix">
                        <label class="col-md-3 control-label">Permissão*</label>
                        <div class="col-md-9">
                            {{ Form::select('permissions', $permissions, null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="col-md-3 control-label">Acesso*</label>
                        <div class="col-md-9">
                            {{ Form::select('permitir', ['1' => 'Permitido', '0' => 'Negado'], null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    {{ Form::submit('Adicionar Permissão', ['class' => 'btn btn-primary', 'id' => 'permission-save-button']) }}
                    <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                    {{ Form::close() }}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
