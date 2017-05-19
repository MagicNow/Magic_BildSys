@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Usuário
        </h1>
        <ol class="breadcrumb" style="right: 40px">
            <li ><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li ><a href="/admin/manage"> Controle de Acesso</a></li>
            <li class="active"><a href="/admin/manage/users"> Usuários</a></li>
        </ol>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'patch']) !!}

                        @include( 'flash::message' )
                        @include('admin.manage.users.fields')

                   {!! Form::close() !!}

                   @include('admin.manage.users.permissao')
               </div>
           </div>
       </div>
   </div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function() {
            $("#permissoesSelect").select2({
                dropdownParent: $("#permission-modal-{{ $user->id }}"),
                theme: 'bootstrap',
                placeholder: "-",
                language: "pt-BR",
                allowClear: true
            });
            $("#rolesSelect").select2({
                dropdownParent: $("#role-modal-{{ $user->id }}"),
                theme: 'bootstrap',
                placeholder: "-",
                language: "pt-BR",
                allowClear: true
            });
        });
    </script>
@stop
