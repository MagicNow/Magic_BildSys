@extends('layouts.front')
@section('styles')
    <style type="text/css">
        textarea {
            resize: none;
        }

        .radio-inline {
            font-size: 11px;
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            @if(auth()->user()->fornecedor)
                Enviar Proposta
            @else
                Informar valores de fornecedor
            @endif
        </h1>
    </section>

    {!!
      Form::open([
          'route' => ['quadroDeConcorrencia.informar-valor', $quadro->id],
          'id' => 'informar-valores-form',
          'class' => 'content'
        ])
      !!}

    <input type="hidden" value="{{ (int) $quadro->hasServico() }}" name="has_servico">

    @if($errors->count())
        <div class="alert alert-danger">
            <p>Por favor, corrija os seguintes problemas para enviar o formulário</p>
            <ul>
                @foreach ($errors->unique() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if(auth()->user()->fornecedor)
                {!! Form::hidden('fornecedor_id', auth()->user()->fornecedor->id) !!}
                <h3>{{ auth()->user()->fornecedor->nome }}</h3>
            @else
                <div class="form-group">
                    {!!
                      Form::select(
                        'fornecedor_id',
                        $fornecedores,
                        old('fornecedor_id'),
                        [ 'class' => 'select2 form-control' ]
                      )
                    !!}
                </div>
            @endif
        </div>

    </div>
    <div class="box box-solid">
        <div class="box-body table-responsive">
            <table class="table table-responsive table-striped table-align-middle table-condensed">
                <thead>
                <tr>
                    <th class="text-center">Obra - Cidade</th>
                    <th class="text-center">Código</th>
                    <th class="text-center">Descrição</th>
                    <th class="text-center">Un. de Medida</th>
                    <th class="text-center">Observações ao fornecedor:</th>
                    <th class="text-center">Quantidade</th>
                    <th class="text-center">Valor Unitário</th>
                    <th class="text-center">Valor Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($quadro->itens as $item)
                    <tr class="js-calc-row">
                        <td>
                            @foreach($item->ordemDeCompraItens->pluck('obra')->flatten()->unique() as $key => $obra)
                                {{ $obra->nome }} - {{ $obra->cidade->nome }}{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        </td>
                        <td>{{ $item->insumo->codigo }}</td>
                        <td>{{ $item->insumo->nome }}</td>
                        <td>{{ $item->insumo->unidade_sigla }}</td>
                        <td>
                            {!!
                              Form::textarea(
                                "itens[{$item->id}][obs]",
                                $item->obs,
                                [
                                  'placeholder' => 'Observação',
                                  'class' => 'form-control',
                                  'rows' => 2,
                                  'cols' => 25,
                                  'disabled' => 'disabled',
                                  'style' => 'cursor: auto;background-color: transparent;resize: vertical;'
                                ]
                              )
                            !!}
                        </td>
                        <td class="js-calc-amount">
                            {{ number_format($item->qtd,2,',','.') }}
                            {!! Form::hidden("itens[{$item->id}][qtd]", $item->qtd) !!}
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">R$</span>
                                {!!
                                  Form::text(
                                    "itens[{$item->id}][valor_unitario]",
                                    old("itens[{$item->id}][valor_unitario]"),
                                    [
                                              'class' => 'form-control js-calc-price money',
                                    ]
                                  )
                                !!}
                            </div>
                        </td>
                        <td class="js-calc-result">
                            R$ 0,00
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @php
        $campos_extras = [];
        if($quadro->contrato_template_id && strlen(trim($quadro->contratoTemplate->campos_extras)) ){
            $campos_extras = json_decode($quadro->contratoTemplate->campos_extras);
        }
    @endphp

    <div class="row">

        {{--Frete--}}
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    Frete
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-12" style="margin-bottom: 5px">
                                <label class="radio-inline">
                                    {!!
                                      Form::radio(
                                        "frete_incluso",
                                        '1'
                                      )
                                    !!}
                                    Incluso Valor Unit.
                                </label>
                                <label class="radio-inline">
                                    {!!
                                      Form::radio(
                                        "frete_incluso",
                                        '0'
                                      )
                                    !!}
                                    Não Incluso
                                </label>
                            </div>
                        </div>
                        <div class="row blocoFrete" style="{{ old('frete_incluso')=='1'?'':'display: none;'  }}">
                            <label class="col-md-4">
                                Frete Tipo
                            </label>
                            <div class="col-md-8">
                                <label class="radio-inline">
                                    {!!
                                      Form::radio(
                                        "tipo_frete",
                                        'CIF'
                                      )
                                    !!}
                                    CIF
                                </label>
                                <label class="radio-inline">
                                    {!!
                                      Form::radio(
                                        "tipo_frete",
                                        'FOB'
                                      )
                                    !!}
                                    FOB
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group freteFOB" style="{{ old('tipo_frete')=='FOB'?'':'display: none;'  }}">
                        <div class="row">
                            <label class="col-md-6">
                                Valor
                            </label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">R$</span>
                                    <input type="text"
                                           class="form-control money"
                                           value="{{ old('valor_frete') }}"
                                           name="valor_frete">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @if($quadro->hasServico())

            <div class="col-md-4">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        Porcentagens
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-6">
                                    Mão de Obra
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control percent js-percent"
                                               value="{{ old('porcentagem_servico') }}"
                                               name="porcentagem_servico">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-6">
                                    Material da Contratada
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control percent js-percent"
                                               value="{{ old('porcentagem_material') }}"
                                               name="porcentagem_material">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-6">
                                    Faturamento Direto
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control percent js-percent"
                                               value="{{ old('porcentagem_faturamento_direto') }}"
                                               name="porcentagem_faturamento_direto">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-md-6">
                                    Locação
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control percent js-percent"
                                               value="{{ old('porcentagem_locacao') }}"
                                               name="porcentagem_locacao">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="box box-warning">
                    <div class="box-header with-border">
                        Tipo da Nota Fiscal
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!!
                                      Form::checkbox(
                                        "nf_material",
                                        '1'
                                      )
                                    !!}
                                    Material
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!!
                                      Form::checkbox(
                                        "nf_servico",
                                        '1'
                                      )
                                    !!}
                                    Serviço
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    {!!
                                      Form::checkbox(
                                        "nf_locacao",
                                        '1'
                                      )
                                    !!}
                                    Fatura de Locação
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    </div>


    <div class="row">

        {{--Campos extras Template contrato--}}
        <div class="col-md-12">
            @if(count($campos_extras))
                <div class="box box-primary">
                    <div class="box-header with-border">
                        Condições Comerciais
                    </div>
                    <div class="box-body">
                        @foreach($campos_extras as $campo)
                            @php
                                $v_tag = str_replace('[','', $campo->tag);
                                $v_tag = 'CAMPO_EXTRA[' . str_replace(']','', $v_tag). ']';
                                $tag = mb_strtolower($campo->tag);

                                $eh_telefone = strpos($tag,'telefone');
                                if($eh_telefone === false){
                                    $eh_telefone = strpos($tag,'celular');
                                }
                                $classe = 'form-control';
                                if($eh_telefone !== false){
                                    $classe .=" telefone";
                                }else{
                                    if(strpos($tag,'valor') !== false){
                                        $classe .=" money";
                                    }else if(strpos($tag,'preco') !== false || strpos($tag,'preço') !== false){
                                        $classe .=" money";
                                    }
                                    // cnpj cep cpf
                                    if(strpos($tag,'cep') !== false){
                                        $classe .=" cep";
                                    }
                                    if(strpos($tag,'cnpj') !== false){
                                        $classe .=" cnpj";
                                    }
                                    if(strpos($tag,'cpf') !== false){
                                        $classe .=" cpf";
                                    }
                                }
                            @endphp
                            <div class="form-group">
                                <label class="form-label">
                                    {{ $campo->nome }}
                                </label>

                                @if($campo->tipo =='data')
                                    {!! Form::text($v_tag,null,['placeholder'=>$campo->nome,'class'=>'data_br '.$classe]) !!}
                                @else
                                    @if(strpos($tag,'e-mail') !== false || strpos($tag,'email') !== false)
                                        {!! Form::email($v_tag,null,['placeholder'=>$campo->nome,'class'=>$classe]) !!}
                                    @else
                                        {!! Form::text($v_tag,null,['placeholder'=>$campo->nome,'class'=>$classe,'title'=>$campo->tipo]) !!}
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>


        {{--Equalização técnica--}}

        <div class="col-md-12">

            <div class="box box-danger box-equalizacao-tecnica">
                <div class="box-header with-border">Equalização Técnica</div>
                <div class="box-body">
                    @if($equalizacoes->isEmpty())
                        <p>Sem equalizações técnicas no Quadro de Concorrência</p>
                    @else
                        <table class="table table-responsive table-striped table-align-middle table-condensed">
                            <thead>
                            <tr>
                                <th width="15%">Item</th>
                                <th width="25%">Descrição</th>
                                <th width="20%">Validação</th>
                                <th width="25%">Obs</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($equalizacoes as $key =>  $equalizacao)
                                <tr>
                                    <td class="text-left">{{ $equalizacao->nome }}</td>
                                    <td>{{ $equalizacao->descricao }}</td>

                                    <td>
                                        {!! Form::hidden("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checkable_type]", $equalizacao->getTable()) !!}
                                        {!! Form::hidden("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checkable_id]", $equalizacao->id) !!}
                                        @if($equalizacao->obrigatorio)
                                            <div class="checkbox">
                                                <label>
                                                    {!!
                                                      Form::checkbox(
                                                        "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checked]",
                                                        '1'
                                                      )
                                                    !!}
                                                    Estou ciente
                                                </label>
                                            </div>
                                        @else
                                            <label class="radio-inline">
                                                {!!
                                                  Form::radio(
                                                    "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checked]",
                                                    '1'
                                                  )
                                                !!}
                                                Sim
                                            </label>
                                            <label class="radio-inline">
                                                {!!
                                                  Form::radio(
                                                    "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][checked]",
                                                    '0'
                                                  )
                                                !!}
                                                Não
                                            </label>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$equalizacao->obrigatorio)
                                            {!!
                                              Form::textarea(
                                                "equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][obs]",
                                                old("equalizacoes[{$equalizacao->id}-{$equalizacao->getTable()}][obs]"),
                                                [
                                                  'placeholder' => 'Suas Considerações ou Observações',
                                                  'class' => 'form-control',
                                                  'rows' => 2,
                                                  'cols' => 25
                                                ]
                                              )
                                            !!}
                                        @else
                                            <span class="text-muted"></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                @if($anexos->isNotEmpty())
                    <div class="box-footer text-center">
                        <a href="#modal-anexos" data-toggle="modal" class="btn btn-primary btn-flat">
                            <i class="fa fa-paperclip"></i> Exibir todos os Anexos de Equalização Técnica
                        </a>
                    </div>
                @endif
            </div>
        </div>



        {{--Obrigações da BILD--}}

        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">Obrigações da BILD</div>
                <div class="box-body">
                    {{ $quadro->obrigacoes_bild }}
                </div>

            </div>
        </div>


        {{--Obrigações do Forncedor--}}

        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">Obrigações do Forncedor</div>
                <div class="box-body">
                    {{ $quadro->obrigacoes_fornecedor }}
                </div>

            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <button type="submit"
                    class="btn btn-success btn-flat btn-lg"
                    value="Salvar"
                    id="save">
                <i class='fa fa-save'></i> Salvar
            </button>
            <button type="submit"
                    class="btn btn-danger btn-flat btn-lg pull-left"
                    value="Rejeitar"
                    id="reject">
                <i class='fa fa-times'></i> Declinar
            </button>
        </div>
    </div>
    {!! Form::close() !!}
    <div class="modal fade" id="modal-anexos" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title"> Anexos </h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        @foreach($anexos as $anexo)
                            <li class="list-group-item">
                                <a target="_blank" href="{{ $anexo->url }}">
                                    <i class="fa fa-paperclip"></i> {{ $anexo->nome }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {!!
      Form::select(
        'desistencia_motivo_id',
        $motivos,
        null,
        [
          'class' => 'hidden form-control input-lg',
          'id' => 'desistencia_motivo_id',
          'required' => 'required'
        ]
      )
    !!}
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            floatToMoney
            $('input[name="frete_incluso"]').on('ifToggled', function (event) {
                if (parseInt(event.target.value)) {
                    $('.blocoFrete').hide();
                    $('.freteFOB').hide();
                    $('input[name="valor_frete"]').val('0');
                } else {
                    $('.blocoFrete').show();
                }
            });
            $('input[name="tipo_frete"]').on('ifToggled', function (event) {
                if (event.target.value == 'CIF') {
                    $('.freteFOB').hide();
                    $('input[name="valor_frete"]').val('0');
                } else {
                    $('.freteFOB').show();
                }
            });
            $('input[name="frete_incluso"]:checked, input[name="tipo_frete"]:checked').trigger('ifToggled');
        });
    </script>
@stop
