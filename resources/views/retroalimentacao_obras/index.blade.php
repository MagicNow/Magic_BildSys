@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Retroalimentação de obras
           <a class="btn btn-primary btn-lg btn-flat pull-right"  href="{!! route('retroalimentacaoObras.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('retroalimentacao_obras.table')
            </div>
        </div>
    </div>
@endsection

