@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Solicitações de insumos
           <a class="btn btn-primary pull-right btn-lg btn-flat" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.solicitacaoInsumos.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.solicitacao_insumos.table')
            </div>
        </div>
    </div>
@endsection

