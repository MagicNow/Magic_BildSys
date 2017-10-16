@extends('layouts.front')

@section('content')
    <div class="content-header">
        <h1 class="content-header-title">
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Perfil
        </h1>
    </div>
    <div class="content">
        @if($errors->count())
            <div class="alert alert-danger">
                <p>
                    Por favor, corrija os seguintes problemas para enviar o formulário
                </p>
                <ul>
                    @foreach ($errors->unique() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="box box-muted">
            {!! Form::model($user) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Email</label>
                                {!! Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Senha atual</label>
                                {!! Form::password('current_password', ['id' => 'current_password', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Nova senha</label>
                                {!! Form::password('password', ['id' => 'password', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Confirme nova senha</label>
                                {!! Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
            </div>
            <div class="box-footer">
                <button class="btn btn-success" type="submit">
                    Salvar
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
