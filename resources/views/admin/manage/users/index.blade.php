@extends('layouts.app')

@section('content')
    <section class="content-header">
        <ol class="breadcrumb" style="right: 80px">
            <li ><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><a href="/admin/manage"> Controle de Acesso</a></li>
        </ol>
        <h1 class="pull-left">Usuários</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('users.create') !!}">
               {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.manage.users.table')
            </div>
        </div>
    </div>
@endsection