@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            @if($isEmAprovacao)
                Aprovação de Contrato
            @else
                Detalhes do Contrato
                <button class="btn btn-flat btn-info btn-lg"
                    data-toggle="modal"
                    data-target="#modal-impostos">
                    Impostos
                </button>
            @endif
            @include('contratos.aprovacao')
            @if($contrato->pode_solicitar_entrega)
                <button class="btn btn-flat btn-primary btn-lg"
                    data-toggle="modal"
                    data-target="#modal-entregas">
                    Solicitações de Entrega
                </button>
            @endif
        </h1>
    </section>

	<div class="content">
		@if($contrato->contrato_status_id == 4 || (is_null($contrato->arquivo) && $contrato->contrato_status_id == 5)  )
            {!! Form::open(['url'=>'/contratos/'.$contrato->id.'/envia-contrato', 'files'=> true ]) !!}
            <div class="box box-warning">
                <div class="box-header with-border">
                    Enviar contrato assinado
                </div>
                <div class="box-body">
                    <div class="col-md-10">
                        {!! Form::file('arquivo',['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-flat btn-success btn-block">
                            <i class="fa fa-upload"></i>
                            Enviar
                            {{ $contrato->contrato_status_id == 4? ' e Liberar':'' }}
                        </button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
		@endif
        <section>
            <h6>Dados Informativos</h6>
            <div class="row">
                <div class="col-md-2 form-group">
                    {!! Form::label('id', 'Código do Contrato') !!}
                    <p class="form-control input-lg highlight text-center">{!! $contrato->id !!}</p>
                </div>

                <div class="col-md-4 form-group">
                    {!! Form::label('obra', 'Obra') !!}
                    <p class="form-control input-lg">{!! $contrato->obra->nome !!}</p>
                </div>
                <div class="col-md-2 form-group">
                    {!! Form::label('created_at', 'Data de Criação') !!}
                    <p class="form-control input-lg">{!! $contrato->created_at->format('d/m/Y') !!}</p>
                </div>
                <div class="col-md-4 form-group">
                    {!! Form::label('user_id', 'Responsável') !!}
                    <p class="form-control input-lg">
                        {!!
                            $contrato->quadroDeConcorrencia->user_id
                                ? $contrato->quadroDeConcorrencia->user->name
                                : 'Contrato Automático'
                            !!}
                    </p>
                </div>
            </div>
        </section>
        <section>
            <h6>Dados do Fornecedor</h6>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Nome</label>
                    <p class="form-control input-lg text-limit highlight text-center"
                        title="{!! $contrato->fornecedor->nome !!}">
                        {!! $contrato->fornecedor->nome !!}
                    </p>
                </div>
                <div class="col-md-3 form-group">
                    <label>CNPJ</label>
                    <p class="form-control input-lg">
                        {!! $contrato->fornecedor->cnpj  !!}
                    </p>
                </div>
                <div class="col-md-2 form-group">
                    <label>Telefone</label>
                    <p class="form-control input-lg">
                        {!! $contrato->fornecedor->telefone ?: '<span class="text-danger">Sem telefone</span>'  !!}
                    </p>
                </div>
                <div class="col-md-3 form-group">
                    <label>Email</label>
                    <p class="form-control input-lg text-limit"
                        title="{{ $contrato->fornecedor->email ?: 'Sem email'  }}">
                        {!! $contrato->fornecedor->email ?: '<span class="text-danger">Sem email</span>' !!}
                    </p>
                </div>
            </div>
        </section>
        @include('contratos.timeline')

        @if($isEmAprovacao)
            @include('contratos.table-aprovacao')
        @else
            @include('contratos.table')
            @if($pendencias->isNotEmpty())
                @include('contratos.box-pendencias')
            @endif
        @endif

        <div class="hidden">
            {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
        </div>

    </div>
    <div class="content">
        <a href="{!! route('contratos.index') !!}" class="btn btn-default btn-flat btn-lg">
            <i class="fa fa-arrow-left"></i> {{ ucfirst( trans('common.back') )}}
        </a>
    </div>

    <div class="modal centered-modal fade" id="modal-reapropriar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Reapropriar</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group js-ajax-container"></div>
                    <div class="form-group">
                        <label for="qtd">Quantidade</label>
                        <div class="input-group">
                            {!! Form::text('qtd', null, ['class' => 'form-control money']) !!}
                            <div class="input-group-btn">
                                <button class="btn btn-warning btn-flat" id="add-all">
                                    Tudo
                                </button>
                            </div>
                        </div>
                    </div>
                    @include('partials.grupos-de-orcamento', ['full' => true, 'insumo' => 0])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-flat js-save">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal centered-modal fade" id="modal-reajuste">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Reajuste</h4>
                </div>
                <div class="modal-body js-ajax-container">
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-flat"
                        data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button"
                        class="btn btn-success btn-flat js-save">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal centered-modal fade" id="modal-distrato" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Distrato</h4>
                </div>
                <div class="modal-body js-ajax-container">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-flat js-save">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal centered-modal fade" id="modal-editar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Editar Aditivo</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="qtd">Quantidade</label>
                        {!! Form::text('qtd', null, ['class' => 'form-control money']) !!}
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <div class="input-group">
                            <span class="input-group-addon">R$</span>
                            {!! Form::text('valor', null, ['class' => 'form-control money']) !!}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-flat js-save">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('contratos.modal_impostos')
    @include('contratos.modal_entregas')
@endsection

@section('scripts')
    <script> options_motivos = document.getElementById('motivo').innerHTML; </script>
    <script data-token="{{ csrf_token() }}" src="{{ asset('/js/contrato-actions.js') }}"></script>

    <script>
        function mostrarOcultarAnalise(id, funcao) {
            if(funcao == 'mostrar') {
                $('#analise-'+id).css('display', '');
                $('#btn-analise-reajuste-'+id).attr('onclick', 'mostrarOcultarAnalise('+id+', "ocultar")');
            } else {
                $('#analise-'+id).css('display', 'none');
                $('#btn-analise-reajuste-'+id).attr('onclick', 'mostrarOcultarAnalise('+id+', "mostrar")');
            }
        }
    </script>
@append
