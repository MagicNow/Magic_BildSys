@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Requisições</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('requisicao.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>

            <a class="btn btn-success" style="margin-top: -10px;margin-bottom: 5px" href="/requisicao/ler-qr-cod">
                <i class="fa fa-qrcode" aria-hidden="true"></i> Ler QR Code
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
