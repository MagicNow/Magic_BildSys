@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Memória de Cálculo
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('memoria_calculos.show_fields')
                    <a href="{!! route('memoriaCalculos.index') !!}" class="btn btn-lg btn-flat btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
