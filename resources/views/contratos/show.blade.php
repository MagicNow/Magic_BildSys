@extends('layouts.front')

@section('styles')
    <style type="text/css">
        #totalInsumos h5 {
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }

        #totalInsumos h4 {
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }

        #totalInsumos {
            margin-bottom: 20px;
        }
    </style>
@stop

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
        </h1>
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
                <div class="col-md-2 form-group">
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
                <div class="col-md-4 form-group">
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
    </div>

    <div class="hidden">
        {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
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

  <div class="modal fade" id="modal-impostos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Impostos</h4>
        </div>
        @if(!$isEmAprovacao)
            <div class="modal-body">
                @if($fornecedor->imposto_simples)
                    <h4>
                        {{ $fornecedor->nome }}
                        <span class="label label-info">ALÍQUOTA SIMPLES</span>
                    </h4>
                    <div class="row">
                        <div class="col-sm-3">
                            <table class="table table-no-margin table-bordered">
                                <thead>
                                    <tr>
                                        <th>ISS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($iss as $porcentagem)
                                        <tr>
                                            <td>{{ float_to_money($porcentagem, '') }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-9">
                            <table class="table table-no-margin table-bordered">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th>Inss</th>
                                        <th>Iss</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itens as $item)
                                        <tr>
                                            <td>{{ $item->insumo->nome }}</td>
                                            <td>{{ $item->insumo->cnae->inss ? to_percentage($item->insumo->cnae->inss) : 'Não' }}</td>
                                            <td>{{ $item->insumo->cnae->iss ? to_percentage($item->insumo->cnae->iss) : 'Não' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <h4>
                        {{ $fornecedor->nome }}
                        <span class="label label-info">ALÍQUOTA PRESUMIDA</span>
                    </h4>
                    <table class="table table-no-margin table-bordered">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>ISS</th>
                                <th>INSS</th>
                                <th>IRRF</th>
                                <th>PIS</th>
                                <th>COFINS</th>
                                <th>CSLL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itens as $item)
                                <tr>
                                    <td>{{ $item->insumo->nome }}</td>
                                    @if($item->servico_cnae_id)
                                        <td>{{ to_percentage($item->insumo->cnae->iss) }}</td>
                                        <td>{{ to_percentage($item->insumo->cnae->inss) }}</td>
                                        <td>{{ to_percentage($item->insumo->cnae->irrf) }}</td>
                                        <td>{{ to_percentage($item->insumo->cnae->pis) }}</td>
                                        <td>{{ to_percentage($item->insumo->cnae->cofins) }}</td>
                                        <td>{{ to_percentage($item->insumo->cnae->csll) }}</td>
                                    @else
                                        <td>{{ to_percentage(0) }}</td>
                                        <td>{{ to_percentage(0) }}</td>
                                        <td>{{ to_percentage(0) }}</td>
                                        <td>{{ to_percentage(0) }}</td>
                                        <td>{{ to_percentage(0) }}</td>
                                        <td>{{ to_percentage(0) }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@section('scripts')
    <script> options_motivos = document.getElementById('motivo').innerHTML; </script>
    <script data-token="{{ csrf_token() }}" src="{{ asset('/js/contrato-actions.js') }}"></script>
@append
