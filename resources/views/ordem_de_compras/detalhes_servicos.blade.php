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
                <div class="col-md-6">
                    <span class="pull-left title">
                        <h3>
                            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                             <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <span>Ordem de Compra - Detalhes Serviços</span>
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
            <div class="col-md-4">
                <h6>Total de Insumos</h6>
            </div>
            <div class="col-md-2 text-right borda-direita">
                <h5>ORÇAMENTO INICIAL</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($orcamentoInicial,2,',','.') }}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Nos itens desta O.C.">
                <h5>TOTAL À GASTAR</h5>
                <h4>
                    <small class="pull-left">R$</small>0
                    {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação, como ainda não exixte contrato gerado, tem q estar zerado--}}
                    {{--                    {{ number_format($totalAGastar,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Até o momento em todos os itens desta O.C.">
                <h5>QUANTIDADE REALIZADA</h5>
                <h4>
                    <small class="pull-left">R$</small>0
                    {{---  TO DO = Realizado: São informações que virão com a entrada de NF, sendo assim, no momento não haverá informações--}}
                    {{--                    {{ number_format($realizado,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
                <h5>SALDO</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($orcamentoInicial,2,',','.') }}
                    {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
                    {{--{{ number_format($saldo,2,',','.') }}--}}
                </h4>
            </div>

        </div>

        @foreach($itens as $item)
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-10">
                        <h4 class="highlight">
                            <span class="col-md-7">{{ $item->insumo->codigo . ' - '. $item->insumo->nome }}</span>
                            @php $ordem_de_compras_ids = explode(",", $item->ordem_de_compras_ids) @endphp
                            <ol class="breadcrumb col-md-5" style="padding: 0px;margin-bottom: 0px; background-color:transparent">
                                @foreach($ordem_de_compras_ids as $ordem_de_compra_id)
                                    <li><a href="/ordens-de-compra/detalhes/{{ $ordem_de_compra_id }}" style="font-size:15px;">OC: {{ $ordem_de_compra_id }} </a></li>
                                @endforeach
                            </ol>
                        </h4>
                    </div>
                    <div class="col-md-12 table-responsive margem-topo">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">Unidade Medida</th>
                                <th class="text-center">Qtd. O. Inicial</th>
                                <th class="text-center">Valor O. Inicial</th>
                                <th class="text-center">Qtd. Realizada</th>
                                <th class="text-center">Valor Realizado</th>
                                <th class="text-center">Qtd. Restante</th>
                                <th class="text-center">Valor Restante</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">{{ $item->unidade_sigla . ' - '.$item->unidade->descricao }}</td>
                                <td class="text-center">{{ number_format($item->qtd_inicial, 2, ',','.') }}</td>
                                <td class="text-center"><small class="pull-left">R$</small> {{ number_format($item->preco_inicial, 2, ',','.') }}</td>
                                <td class="text-center">{{ number_format(doubleval($item->qtd_realizada), 2, ',','.') }}</td>
                                <td class="text-center"><small class="pull-left">R$</small> {{ number_format( doubleval($item->valor_realizado), 2, ',','.') }}</td>
                                <td class="text-center">{{ number_format( $item->qtd_inicial-doubleval($item->qtd_realizada), 2, ',','.') }}</td>
                                <td class="text-center"><small class="pull-left">R$</small> {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 table-responsive margem-topo">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">Qtd. Saldo</th>
                                <th class="text-center">Valor Saldo</th>
                                <th class="text-center">Qtd. Solicitada</th>
                                <th class="text-center">Valor Solicitado</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Data de Uso</th>
                                <th class="text-center">Emergencial</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">{{ number_format( $item->qtd_inicial - doubleval($item->qtd_realizada), 2, ',','.') }}</td>
                                <td class="text-center"><small class="pull-left">R$</small> {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}</td>
                                <td class="text-center"><strong>{{ $item->qtd }}</strong></td>
                                <td class="text-center"><small class="pull-left">R$</small> <strong>{{ number_format(doubleval($item->valor_total), 2, ',','.') }}</strong></td>
                                <td class="text-center"><i class="fa fa-circle {{ (($item->qtd_realizada) > $item->qtd_inicial) ? 'text-danger': 'text-success'  }}" aria-hidden="true"></i> </td>
                                <td class="text-center">{{ $item->sugestao_data_uso ? $item->sugestao_data_uso->format('d/m/Y') : ''  }}</td>
                                <td class="text-center">{!! $item->emergencial?'<strong class="text-danger"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> SIM</strong>':'NÃO' !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 margem-topo borda-direita">
                        <div class="row">
                            <div class="col-md-4 label-bloco">
                                Justificativa de compra:
                            </div>
                            <div class="bloco-texto-conteudo col-md-7">
                                {{ $item->justificativa }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 margem-topo">
                        <div class="col-md-4 label-bloco">
                            Observações ao fornecedor:
                        </div>
                        <div class="bloco-texto-conteudo col-md-7">
                            {{ $item->obs }}
                        </div>
                    </div>
                    <div class="col-md-6 margem-topo borda-direita">
                        <div class="row">
                            <div class="col-md-4 label-bloco">
                                Tabela TEMS:
                            </div>
                            <div class="bloco-texto-conteudo col-md-7">
                                {{ $item->tems }}
                            </div>

                            <div class="col-md-4 label-bloco margem-topo">
                                Contrato aditivado:
                            </div>
                            <div class="bloco-texto-conteudo col-md-7 margem-topo">
                                {{ $item->sugestao_contrato_id }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 margem-topo">
                        @if($item->anexos)
                            <div class="col-md-4 label-bloco">
                                Arquivos anexos:
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    @foreach($item->anexos as $anexo)
                                        <div class="bloco-texto-linha col-md-9">{{ substr($anexo->arquivo, strrpos($anexo->arquivo,'/')+1  )  }}</div>
                                        <div class="col-md-2">
                                            <a href="{{ Storage::url($anexo->arquivo) }}" class="btn btn-default btn-block" target="_blank" >
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>

                                    @endforeach
                                </div>

                            </div>
                        @endif

                    </div>

                </div>
            </div>
        @endforeach
        <div class="pg text-center">
            {{ $itens->links() }}
        </div>
    </div>
@endsection