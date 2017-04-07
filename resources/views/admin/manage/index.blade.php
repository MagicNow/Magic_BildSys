@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Dashboard
            <small>Controle de Acesso</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><a href="/admin"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>
    <div class="content">
        <div class="row">

            <div class="col-sm-4">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Gerenciar Usuários
                        </h3>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('manage.users') }}" class="btn btn-default btn-block">
                            Usuários
                        </a>
                    </div>
                </div>

            </div>

            <div class="col-sm-4">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                           Gerenciar Perfis
                        </h3>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('manage.roles') }}" class="btn btn-default btn-block">
                            Perfis
                        </a>
                    </div>
                </div>

            </div>

            <div class="col-sm-4">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Gerenciar Permissões
                        </h3>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('manage.permissions') }}" class="btn btn-default btn-block">
                            Permissões
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection
