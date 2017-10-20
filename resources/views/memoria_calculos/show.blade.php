@extends('layouts.front')

@section('content')
    <section class="content-header ">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Memória de cálculo
        </h1>
    </section>
    <div class="content memoria">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('memoria_calculos.show_fields')
                    <a href="{!! route('memoriaCalculos.index') !!}" class="btn btn-warning btn-flat btn-lg">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
