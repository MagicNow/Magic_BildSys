@extends('layouts.front')

@section('content')
  <section class="content-header">
      <h1 class="pull-left">Informar valores fornecedor</h1>
  </section>
  {!!
    Form::open([
      'route' => ['quadro-de-concorrencias.informar-valor', $quadro->id]
    ])
  !!}
    <div class="clearfix"></div>
      @include('flash::message')
    <div class="clearfix"></div>

  <div class="box box-solid">
    <div class="box-body">
      <div class="row">
        <div class="col-md-3">
          <div class="row">
            <div class="col-md-12">
                <a href="#"
                  class="btn btn-primary btn-block btn-lg js-sweetalert"
                  data-message="{{ $quadro->obrigacoes_fornecedor }}">
                  Obrigações do Fornecedor
                </a>
                <a href="#"
                  class="btn btn-primary btn-block btn-lg js-sweetalert"
                  data-message="{{ $quadro->obrigacoes_bild }}">
                  Obrigações BILD
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="box box-muted box-equalizacao-tecnica">
              <div class="box-header with-border">Equalização Técnica</div>
              <div class="box-body">
                <table class="table table-responsive table-striped table-align-middle">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Item</th>
                      <th>Sim/Não/Ciência</th>
                      <th>Obs</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($equalizacoes as $key =>  $equalizacao)
                      <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $equalizacao->nome }}</td>
                        @if($equalizacao->obrigatorio)
                          <td>
                            <div class="checkbox">
                              <label>
                                {!! Form::checkbox("equalizacoes[{$equalizacao->id}][checked]", '1', true) !!}
                                Obrigatório
                              </label>
                            </div>
                          </td>
                        @else
                          <td>
                            <label class="radio-inline">
                              {!!
                                Form::radio(
                                  "equalizacoes[{$equalizacao->id}][checked]",
                                  '1'
                                )
                              !!}
                             Sim
                            </label>
                            <label class="radio-inline">
                              {!!
                                Form::radio(
                                  "equalizacoes[{$equalizacao->id}][checked]",
                                  '0'
                                )
                              !!}
                              Não
                            </label>
                          </td>
                        @endif
                        <td>
                          @if(!$equalizacao->obrigatorio)
                            {!!
                              Form::textarea(
                                "equalizacoes[{$equalizacao->id}][obs]",
                                old("equalizacoes[{$equalizacao->id}][obs]"),
                                [
                                  'placeholder' => 'Observação',
                                  'class' => 'form-control',
                                  'rows' => 3,
                                  'cols' => 25
                                ]
                              )
                            !!}
                          @else
                            <span class="text-muted">#</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="box-footer">
                <a href="#modal-anexos-eq" data-toggle="modal" class="btn btn-primary">
                  Anexos da Equalização Técnica
                </a>
                <a href="#modal-anexos-qc" data-toggle="modal" class="btn btn-primary">
                  Anexos do Quadro de Concorrencias
                </a>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
  <div class="box box-solid">
    <div class="box-body">
      <table class="table table-responsive table-striped table-align-middle">
        <thead>
          <tr>
            <th>Cod. Insumo</th>
            <th>Un</th>
            <th>Obs. Fornecedor</th>
            <th>Quantidade QC</th>
            <th>Tabela Tems</th>
            <th>Valor Unitário</th>
            <th>Valor Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($quadro->itens as $item)
            <tr class="js-calc-row">
              <td>{{ $item->insumo->codigo }}</td>
              <td>{{ $item->insumo->unidade_sigla }}</td>
              <td>
                {!!
                  Form::textarea(
                    "item[{$item->id}][obs]",
                    old("item[{$item->id}][obs]"),
                    [
                      'placeholder' => 'Observação',
                      'class' => 'form-control',
                      'rows' => 3,
                      'cols' => 25
                    ]
                  )
                !!}
              </td>
              <td class="js-calc-amount">
                {{ $item->qtd }}
              </td>
              <td>
                {!!
                  Form::textarea(
                    "item[{$item->id}][tems]",
                    old("item[{$item->id}][tems]"),
                    [
                      'placeholder' => 'Tems',
                      'class' => 'form-control',
                      'rows' => 3,
                      'cols' => 25
                    ]
                  )
                !!}
              </td>
              <td>
                {!!
                  Form::text(
                    "item[{$item->id}][valor]",
                    old("item[{$item->id}][valor]"),
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
    <div class="box-footer text-right">
      <button class="btn btn-danger">Rejeitar</button>
      <button class="btn btn-success">Salvar</button>
    </div>
  </div>
{!! Form::close() !!}
<div class="modal fade" id="modal-anexos-eq" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Anexos da Equalização Técnica </h4>
      </div>
      <div class="modal-body">
        <ul>
          @foreach($anexos as $anexo)
            <li>
              <a target="_blank" href="{{ $anexo->url }}">
                {{ $anexo->nome }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-anexos-qc" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Anexos do Quadro de Concorrencias </h4>
      </div>
      <div class="modal-body">
        <ul>
          @foreach($quadro->anexos as $anexo)
            <li>
              <a target="_blank" href="{{ $anexo->url }}">
                {{ $anexo->nome }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection

