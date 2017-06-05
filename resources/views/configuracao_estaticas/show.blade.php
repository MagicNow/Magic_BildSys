@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Configuração
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('configuracao_estaticas.show_fields')
                    <a href="{!! route('configuracaoEstaticas.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
