@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Qc Item Qc Fornecedors
            <a class="btn btn-primary pull-right"  href="{!! route('qcItemQcFornecedors.create') !!}">{{ ucfirst( trans('common.new') )}}</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>



        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('qc_item_qc_fornecedors.table')
            </div>
        </div>
    </div>
@endsection

