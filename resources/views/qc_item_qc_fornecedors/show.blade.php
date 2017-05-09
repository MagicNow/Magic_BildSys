@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Qc Item Qc Fornecedor
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('qc_item_qc_fornecedors.show_fields')
                    <a href="{!! route('qcItemQcFornecedors.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
