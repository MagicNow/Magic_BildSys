@extends('layouts.front')

@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <span class="pull-left title">
                   <h3>
                       <button type="button" class="btn btn-link" onclick="history.go(-1);">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                       </button>
                       <span>Criar previsão de memória de cálculo</span>
                   </h3>
                </span>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="clearfix"></div>

        <div class="form-group col-md-2">
            {!! Form::label('contrato', 'Contrato:') !!}
            <p class="form-control">{!! $contrato->id !!}</p>
        </div>

        <div class="form-group col-md-4">
            {!! Form::label('fornecedor', 'Fornecedor:') !!}
            <p class="form-control">{!! $contrato->fornecedor->nome !!}</p>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('insumo', 'Insumo:') !!}
            <p class="form-control">{!! $contrato_item_apropriacao->codigo_insumo . ' - ' . $insumo->nome . ' - ' . $insumo->unidade_sigla!!}</p>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('tarefa', 'Tarefa:') !!}
            {!! Form::select('tarefa', $tarefas , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('memoria_de_calculo', 'Memória de cálculo:') !!}
            <a href="/memoriaCalculos/create"
               class="btn btn-flat btn-sm btn-primary pull-right"
               data-toggle="tooltip"
               data-placement="top"
               title="Criar memória de cálculo"
               style="margin-top: -10px;">
                <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
            </a>
            {!! Form::select('memoria_de_calculo', $memoria_de_calculo , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>
    </div>
@endsection
