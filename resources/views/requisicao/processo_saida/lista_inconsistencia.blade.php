@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Lista de inconsistências
        </h1>
    </section>
    <div class="content">
        <div class="form-group col-md-3">
            {!! Form::label('requisicao', 'Requisição Nro:') !!}
            <p class="form-control">{!! $requisicao->id !!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('status', 'Status:') !!}
            <p class="form-control">{!! $requisicao->status !!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('data', 'Data:') !!}
            <p class="form-control">{!! $requisicao->created_at->format('d/m/Y') !!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('solicitante', 'Solicitante:') !!}
            <p class="form-control">{!! $requisicao->user->name !!}</p>
        </div>

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('requisicao.processo_saida.lista_inconsistencia_table')
            </div>
        </div>

        <div class="col-md-12">
            <a href="{{ route('requisicao.processoSaida', $requisicao->id) }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
            </a>
        </div>
    </div>
@endsection