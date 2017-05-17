@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Qc Fornecedor Equalizacao Checks</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('qcFornecedorEqualizacaoChecks.create') !!}">
            {{ ucfirst( trans('common.new') )}}
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('qc_fornecedor_equalizacao_checks.table')
            </div>
        </div>
    </div>
@endsection

