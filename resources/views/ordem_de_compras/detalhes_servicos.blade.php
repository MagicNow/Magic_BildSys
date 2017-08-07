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
                    <span id="valor_previsto"></span>
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
                    <small class="pull-left">R$</small>
                    <span id="valor_comprometido_a_gastar"></span>
                    {{---  TO DO = A gastar: É a soma de todos os saldos de contratos na que apropriação--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
                <h5>SALDO DE ORÇAMENTO</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="saldo_orcamento"></span>
                    {{--- TO DO = Saldo: Previsto - Realizado - A gastar--}}
                    {{--{{ number_format($saldo,2,',','.') }}--}}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita">
                <h5>VALOR DA OC</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="valor_oc"></span>
                </h4>
            </div>
            <div class="col-md-2 text-right">
                <h5>SALDO DISPONÍVEL</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    <span id="saldo_disponivel"></span>
                </h4>
            </div>
        </div>

        <div class="content">
            @include('ordem_de_compras.obras-insumos-table')
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(function () {
            recalcularAnaliseServico();
        });

        function recalcularAnaliseServico() {
            var valor_previsto = 0;
            var valor_comprometido_a_gastar = 0;
            var saldo_orcamento = 0;
            var valor_oc = 0;
            var saldo_disponivel = 0;
            var tem_checked = false;

            $('.detalhes_servicos_itens').each(function (index, value) {
                if($(value).prop('checked')) {
                    tem_checked = true;
                    valor_previsto += parseInt($(value).attr('valor_previsto'));
                    valor_comprometido_a_gastar += parseInt($(value).attr('valor_comprometido_a_gastar'));
                    saldo_orcamento += parseInt($(value).attr('saldo_orcamento'));
                    valor_oc += parseInt($(value).attr('valor_oc'));
                    saldo_disponivel += parseInt($(value).attr('saldo_disponivel'));
                }
            });

            if(tem_checked) {
                $('#valor_previsto').text(floatToMoney(valor_previsto, ''));
                $('#valor_comprometido_a_gastar').text(floatToMoney(valor_comprometido_a_gastar, ''));
                $('#saldo_orcamento').text(floatToMoney(saldo_orcamento, ''));
                $('#valor_oc').text(floatToMoney(valor_oc, ''));
                $('#saldo_disponivel').text(floatToMoney(saldo_disponivel, ''));
            } else {
                $('#valor_previsto').text('{{ number_format($orcamentoInicial,2,',','.') }}');
                $('#valor_comprometido_a_gastar').text('{{ number_format($valor_comprometido_a_gastar,2,',','.') }}');
                $('#saldo_orcamento').text('{{ number_format($orcamentoInicial,2,',','.') }}');
                $('#valor_oc').text('{{ number_format($totalSolicitado,2,',','.') }}');
                $('#saldo_disponivel').text('{{ number_format(($orcamentoInicial - $totalSolicitado),2,',','.') }}');
            }
        }
    </script>
@endsection