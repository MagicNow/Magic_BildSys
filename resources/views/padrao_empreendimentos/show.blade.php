@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Padrão de empreendimento
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body for-padrao">
                <div class="row" style="padding-left: 20px">
                    @include('padrao_empreendimentos.show_fields')
                    <a href="{!! route('padraoEmpreendimentos.index') !!}" class="btn btn-warning">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
