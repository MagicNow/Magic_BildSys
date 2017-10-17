@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Medições de serviços
           <a class="btn btn-primary pull-right btn-lg btn-flat" href="{!! route('medicoes.preCreate') !!}">
            <i class="fa fa-plus"></i> Nova Medição
           </a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        <div class="box box-primary">
            <div class="box-body">
                    @include('medicao_servicos.table')
            </div>
        </div>
    </div>
@endsection

