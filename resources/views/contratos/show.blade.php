@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
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

            @if($contrato->contrato_status_id == 5 && $contrato->hasServico() )
                <a href="{{ Storage::url($contrato->arquivo) }}" download="minuta_{{ $contrato->id }}.pdf" target="_blank"
                   class="btn btn-lg btn-flat btn-info pull-right" title="Baixar minuta assinada assinada pelo fornecedor">
                    <i class="fa fa-download"></i>
                </a>
            @endif
            <a href="{{ route('contratos.imprimirEspelhoContrato', $contrato->id) }}" download="espelho_contrato_{{ $contrato->id }}.pdf" target="_blank"
               class="btn btn-lg btn-flat btn-warning pull-right" title="Baixar espelho do contrato">
                <i class="fa fa-files-o"></i>
            </a>
            <a href="{{ route('contratos.imprimirContratoCompleto', $contrato->id) }}" download="contrato_{{ $contrato->id }}.pdf" target="_blank"
               class="btn btn-lg btn-flat btn-success pull-right" title="Baixar contrato completo">
                <i class="fa fa-print"></i>
            </a>

            @if($contrato->pode_solicitar_entrega)
                <button class="btn btn-flat btn-primary btn-lg"
                    data-toggle="modal"
                    data-target="#modal-entregas">
                    Solicitações de Entrega
                </button>
            @endif

            @if($contrato->isStatus(\App\Models\ContratoStatus::ATIVO))
                    @shield('pagamentos.create')
                    <a class="btn btn-flat bg-olive btn-lg"
                       href="{!! route('pagamentos.create').'?contrato_id='.$contrato->id !!}">
                        <i class="fa fa-usd"></i> Incluir Pagamento
                    </a>
                    @endshield
            @endif
        </h1>
    </section>
	<div class="content">
		@if($contrato->hasServico())
            @if($contrato->contrato_status_id == 4 || (is_null($contrato->arquivo) && $contrato->contrato_status_id == 5))
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
		@endif
        <section>
            <h6>Dados Informativos</h6>
            <div class="row">
                <div class="col-md-2 form-group">
                    {!! Form::label('id', 'Número do Contrato') !!}
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
            @php $espelho = null; @endphp
            @include('contratos.table', compact('espelho'))
            @if($pendencias->isNotEmpty())
                @include('contratos.box-pendencias')
            @endif
        @endif

        <div class="hidden">
            {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
        </div>

    </div>
    <div class="content">
        @if($contrato->pagamentos)
        <div class="box box-muted">
            <div class="box-header">
                <h3 class="box-title">
                    Pagamentos
                </h3>
                @shield('pagamentos.create')
                <a class="btn btn-flat bg-olive btn-xs pull-right"
                   href="{!! route('pagamentos.create').'?contrato_id='.$contrato->id !!}">
                    <i class="fa fa-usd"></i> Incluir Pagamento
                </a>
                @endshield
            </div>
            <div class="box-body">
                @include('pagamentos.table')
            </div>
        </div>
        @endif
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
                    <div class="form-group">
                        <label for="descricao">Observação</label>
                        <textarea class="form-control" name="descricao"></textarea>
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
    @parent
    <script> options_motivos = document.getElementById('motivo').innerHTML; </script>
    <script data-token="{{ csrf_token() }}" src="{{ asset('/js/contrato-actions.js') }}"></script>

    <script>
        function selectgrupo(id, change, tipo){
            var rota = "{{url('ordens-de-compra/grupos')}}/";
            if(tipo == 'servicos'){
                rota = "{{url('ordens-de-compra/servicos')}}/";
            }
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id,
                    data: {
                        obra_id: {{ $contrato->obra_id }},
                        campo_join: change
                    }
                }).done(function(retorno) {
                    options = '';
                    options = '<option value="">Selecione</option>';
                    $('#'+change).html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#'+change).html(options);
                    $('#'+change).attr('disabled',false);

                    $('#cadastrar_'+change).css('display', '');
                });
            }else{
                if(change == 'subgrupo1_id'){
                    $('#subgrupo1_id').val(null).trigger('change');
                    $('#subgrupo2_id').val(null).trigger('change');
                    $('#subgrupo3_id').val(null).trigger('change');
                    $('#servico_id').val(null).trigger('change');

                    $('#subgrupo1_id').attr('disabled',true);
                    $('#subgrupo2_id').attr('disabled',true);
                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }else if(change == 'subgrupo2_id'){
                    $('#subgrupo2_id').val(null).trigger('change');
                    $('#subgrupo3_id').val(null).trigger('change');
                    $('#servico_id').val(null).trigger('change');

                    $('#subgrupo2_id').attr('disabled',true);
                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }else if(change == 'subgrupo3_id'){
                    $('#subgrupo3_id').val(null).trigger('change');
                    $('#servico_id').val(null).trigger('change');

                    $('#subgrupo3_id').attr('disabled',true);
                    $('#servico_id').attr('disabled',true);
                }else if(change == 'servico_id'){
                    $('#servico_id').attr('disabled',true);
                }
            }
        }

        $(function () {
            selectgrupo( $('#grupo_id').val() , 'subgrupo1_id', 'grupos');
        });
    </script>
@append
