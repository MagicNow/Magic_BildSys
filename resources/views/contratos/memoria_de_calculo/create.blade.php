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
                       <span>Criar memória de cálculo</span>
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
            {!! Form::select('tarefa', ['' => 'Selecione uma tarefa']+$tarefas , null, ['class' => 'form-control select2', 'required' => 'required']) !!}
        </div>
    </div>
@endsection
