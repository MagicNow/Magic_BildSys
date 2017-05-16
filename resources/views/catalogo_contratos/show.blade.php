@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Catálogo de Acordo
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('catalogo_contratos.show_fields')
                    <a href="{!! route('catalogo_contratos.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
