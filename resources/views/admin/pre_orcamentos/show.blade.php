@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Pré orçamento
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.pre_orcamentos.show_fields')
                    <a href="{!! route('admin.pre_orcamentos.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
