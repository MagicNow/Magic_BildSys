@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Requisições</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('requisicao.create') !!}">
               <i class="fa fa-plus" aria-hidden="true"></i> Criar requisição
           </a>

            <a class="btn btn-info" style="margin-top: -10px;margin-bottom: 5px" href="/requisicao/aplicacao-estoque/local">
                <i class="fa fa-qrcode" aria-hidden="true"></i> Aplicação de estoque
            </a>
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

