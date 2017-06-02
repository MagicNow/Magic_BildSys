@extends('layouts.front')
@section('styles')
<style type="text/css">
    textarea {
        resize: none;
    }
    .radio-inline{
        font-size: 11px;
    }
</style>
@stop
@section('content')
  <section class="content-header">
    <h1>
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
      <div class="col-md-6">
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
      <div class="col-md-3">
          <a href="#modal-fornecedor"
             data-toggle="modal"
             class="btn btn-link btn-block">
              <i class="fa fa-info"></i> Obrigações do Fornecedor
          </a>
      </div>
      <div class="col-md-3">
          <a href="#modal-bild"
             data-toggle="modal"
             class="btn btn-link btn-block">
              <i class="fa fa-info"></i> Obrigações BILD
          </a>
      </div>
  </div>
  <div class="box box-solid">
      <div class="box-body">
          <table class="table table-responsive table-striped table-align-middle table-condensed">
              <thead>
              <tr>
                  <th>Obra - Cidade</th>
                  <th>Insumo</th>
                  <th>Obs. Fornecedor</th>
                  <th>Quantidade</th>
                  <th>Un</th>
                  <th>Valor Unitário</th>
                  <th>Valor Total</th>
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
                      <td>{{ $item->insumo->nome }}</td>
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
                              ]
                            )
                          !!}
                      </td>

                      <td class="js-calc-amount">
                          {{ number_format($item->qtd,2,',','.') }}
                          {!! Form::hidden("itens[{$item->id}][qtd]", $item->qtd) !!}
                      </td>
                      <td>{{ $item->insumo->unidade_sigla }}</td>
                      <td>
                          {!!
                            Form::text(
                              "itens[{$item->id}][valor_unitario]",
                              old("itens[{$item->id}][valor_unitario]"),
                              [
                                        'class' => 'form-control js-calc-price money',
                              ]
                            )
                          !!}
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


  <div class="row">
          <div class="col-md-3">
              @if($quadro->hasMaterial())
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
              @endif

            @if($quadro->hasServico())
            <div class="row">
              <div class="col-md-12">

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
                              '1',
                              old('nf_servico',true)
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
            </div>
            @endif
          </div>
          <div class="col-md-9">
            <div class="box box-danger box-equalizacao-tecnica">
              <div class="box-header with-border">Equalização Técnica</div>
              <div class="box-body">
                <table class="table table-responsive table-striped table-align-middle table-condensed">
                  <thead>
                    <tr>
                      <th width="10%">Detalhes</th>
                      <th>Item</th>
                      <th width="20%">Sim/Não/Ciência</th>
                      <th width="25%">Obs</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($equalizacoes as $key =>  $equalizacao)
                      <tr>
                        <td>
                          <button type="button"
                            class="btn btn-default btn-flat btn-xs js-sweetalert"
                            data-title="{{ $equalizacao->nome }}"
                            data-text="{{ $equalizacao->descricao }}">
                            <i class="fa fa-info-circle"></i> detalhes
                          </button>
                        </td>
                        <td class="text-left">{{ $equalizacao->nome }}</td>
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
              </div>
              <div class="box-footer text-center">
                <a href="#modal-anexos" data-toggle="modal" class="btn btn-primary btn-flat">
                 <i class="fa fa-paperclip"></i> Exibir todos os Anexos de Equalização Técnica
                </a>
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
                <i class='fa fa-times'></i>  Rejeitar
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
                    <i class="fa fa-paperclip"></i>  {{ $anexo->nome }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-fornecedor" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"> Obrigações do Fornecedor </h4>
          </div>
          <div class="modal-body">
            {{ $quadro->obrigacoes_fornecedor }}
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modal-bild" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"> Obrigações Bild </h4>
          </div>
          <div class="modal-body">
            {{ $quadro->obrigacoes_bild }}
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
        $('input[name="frete_incluso"]').on('ifToggled', function(event){
            if(parseInt(event.target.value)){
                $('.blocoFrete').hide();
                $('.freteFOB').hide();
                $('input[name="valor_frete"]').val('0');
            }else{
                $('.blocoFrete').show();
            }
        });
        $('input[name="tipo_frete"]').on('ifToggled', function(event){
            if(event.target.value=='CIF'){
                $('.freteFOB').hide();
                $('input[name="valor_frete"]').val('0');
            }else{
                $('.freteFOB').show();
            }
        });
    });
</script>
@stop
