@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Qc Fornecedor Equalizacao Checks
            <a class="btn btn-primary pull-right"  href="{!! route('qcFornecedorEqualizacaoChecks.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
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

