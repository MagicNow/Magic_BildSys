@extends('layouts.front')
@section('styles')
    <style type="text/css">

        #totalInsumos h5{
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }
        #totalInsumos h4{
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }
        #totalInsumos{
            margin-bottom: 20px;
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-9">
                    <span class="pull-left title">
                        <h3>
                            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                             <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <span>Ordem de compra - Análise do Orçamento - Nível serviço</span>
                        </h3>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <h6>Dados Informativos</h6>
        <div class="row">
            <div class="col-md-2 form-group">
                {!! Form::label('codigo', 'Código do serviço') !!}
                <p class="form-control input-lg highlight text-center">{!! $servico->codigo !!}</p>
            </div>

            <div class="col-md-10 form-group">
                {!! Form::label('servico', 'Serviço') !!}
                <p class="form-control input-lg">{!! $servico->nome !!}</p>
            </div>
        </div>
        <hr>
        <div class="row" id="totalInsumos">
            <div class="col-md-2 text-right borda-direita">
                <h5>Valor previsto no orçamento</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($orcamentoInicial,2,',','.') }}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Até o momento em todos os itens desta O.C.">
                <h5>Valor comprometido realizado</h5>
                <h4>
                    <small class="pull-left">R$</small>0,00
                    {{---  TO DO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
                    {{--                    {{ number_format($realizado,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Nos itens desta O.C.">
                <h5>Valor comprometido à gastar</h5>
                <h4>
                    <small class="pull-left">R$</small>0,00
                    {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação, como ainda não exixte contrato gerado, tem q estar zerado--}}
                    {{--                    {{ number_format($totalAGastar,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
                <h5>SALDO DE ORÇAMENTO</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($orcamentoInicial,2,',','.') }}
                    {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
                    {{--{{ number_format($saldo,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita">
                <h5>VALOR DA OC</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($totalSolicitado,2,',','.') }}
                </h4>
            </div>
            <div class="col-md-2 text-right">
                <h5>SALDO DISPONÍVEL</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format(($orcamentoInicial - $totalSolicitado),2,',','.') }}
                </h4>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-md-12 table-responsive margem-topo">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th class="text-center">Código do insumo</th>
                            <th class="text-center">Descrição do insumo</th>
                            <th class="text-center">Und de medida</th>
                            <th class="text-center">Valor previsto no orçamento</th>
                            <th class="text-center">Valor comprometido realizado</th>
                            <th class="text-center">Valor comprometido à gastar</th>
                            <th class="text-center">Saldo de orçamento</th>
                            <th class="text-center">Valor da O.C.</th>
                            <th class="text-center">Saldo disponível</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($itens as $item)
                            <tr>
                                <td class="text-center">{{ $item->insumo->codigo }}</td>
                                <td class="text-center">{{ $item->insumo->nome }}</td>
                                <td class="text-center">{{ $item->unidade_sigla }}</td>
                                <td class="text-center">
                                    <small class="pull-left">R$</small>
                                    {{ number_format($item->preco_inicial, 2, ',','.') }}
                                </td>
                                <td class="text-center">
                                    <small class="pull-left">R$</small>
                                    {{ number_format( doubleval($item->valor_realizado), 2, ',','.') }}
                                </td>
                                <td class="text-center">
                                    <small class="pull-left">R$</small>0,00
                                </td>
                                <td class="text-center">
                                    <small class="pull-left">R$</small>
                                    {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}
                                </td>
                                <td class="text-center">
                                    <small class="pull-left">R$</small> <strong>{{ number_format(doubleval($item->valor_total), 2, ',','.') }}</strong>
                                </td>
                                <td class="text-center">
                                    <small class="pull-left">R$</small>
                                    {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado)-doubleval($item->valor_total), 2, ',','.') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pg text-center">
            {{ $itens->links() }}
        </div>
    </div>
@endsection