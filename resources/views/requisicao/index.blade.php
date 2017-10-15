@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Requisições
            <div class="pull-right">
                <a class="btn btn-primary"  href="{!! route('requisicao.create') !!}"><i class="fa fa-plus" aria-hidden="true"></i> Criar requisição</a>
                <a class="btn btn-info"  href="/requisicao/aplicacao-estoque/local"><i class="fa fa-qrcode" aria-hidden="true"></i> Aplicação de estoque</a>
            </div>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('requisicao.table')
            </div>
        </div>
    </div>
@endsection

