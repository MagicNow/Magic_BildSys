@extends('layouts.front')

@section('content')
    <style type="text/css">
        .table-bordered td {
            border: 1px solid #5a5555;
        }
        .trecho{
            cursor: pointer;
            border: 2px solid #f98d00 !important;
        }
        .trechoMedido{
            cursor: pointer;
            border: 2px solid #dd4b39 !important;
        }
        .trechoMedido100porcento{
            border: 2px solid #00a65a !important;
        }
        .trechoVazio{
            border: 1px solid #5a5555 !important;
        }
    </style>
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Medicão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-default">
            <div class="box-body">
                <h4>Obra: <span class="label bg-orange">{{ $contratoItemApropriacao->contratoItem->contrato->obra->nome }}</span></h4>
                <h4>
                    Contrato: <span class="label bg-navy">{{ $contratoItemApropriacao->contratoItem->contrato->id }}</span>
                    <span class="label bg-navy">{{ $contratoItemApropriacao->contratoItem->contrato->fornecedor->nome }}</span>
                </h4>
                <h4>Insumo: <span class="label label-warning">{{ $contratoItemApropriacao->codigo_insumo }}</span> <span class="label label-warning">{{ $contratoItemApropriacao->insumo->nome }}</span></h4>
                @if($medicaoServico)
                    <h4>Período do Serviço: <span class="label label-default"> {{ with(new\Carbon\Carbon($medicaoServico->periodo_inicio))->format('d/m/Y') }}</span> à <span class="label label-default">{{ with(new\Carbon\Carbon($medicaoServico->periodo_termino))->format('d/m/Y') }}</span></h4>
                @endif
                @if($mcMedicaoPrevisao)
                    <h4>{{ $mcMedicaoPrevisao->memoriaCalculoBloco->estruturaObj->nome }} -
                        {{ $mcMedicaoPrevisao->memoriaCalculoBloco->pavimentoObj->nome }} -
                        {{ $mcMedicaoPrevisao->memoriaCalculoBloco->trechoObj->nome }}
                        <span class="label label-default">
                            {{ float_to_money($mcMedicaoPrevisao->qtd,'') .' '. $mcMedicaoPrevisao->unidade_sigla }}
                        </span>
                        @if($medicoes)
                            @if($medicoes->count())
                            <span class="label label-warning">
                                {{ number_format( ( ($medicoes->first()->qtd/$mcMedicaoPrevisao->qtd) * 100),2,',','.')  }}% já medido
                            </span>
                            @endif
                        @endif
                    </h4>
                @endif
            </div>
        </div>
        @if( !is_null($medicaoServico) && is_null($mcMedicaoPrevisao))

        <div class="box box-warning" id="blocos">
            <div class="box-body">
                @if(isset($blocos))
                    @foreach($blocos as $blocoIndex => $bloco)
                        <div class="row">
                            <div class="col-sm-12 text-left"> {{ $bloco['nome'] }} </div>
                            <div class="col-sm-12">
                                <table class="table table-condensed" style="width: {{ $bloco['largura'] }}%; margin:5px auto;">

                                    @if (count($bloco['itens'])== 0)
                                        <tr>
                                            <td colspan="2"> &nbsp; </td>
                                        </tr>
                                    @else
                                        @foreach($bloco['itens'] as $pavimentoIndex => $pavimento)
                                            <tr>
                                                <td class="warning" width="15%">{{ $pavimento['nome'] }}</td>
                                                <td style="padding: 0px !important;">
                                                    @if(count($pavimento['itens'])==0)
                                                        <table  style="width:{{ $pavimento['largura'] }}%;
                                                                margin:0px auto; min-height: 31px;">
                                                            <tr>
                                                                <td> &nbsp;</td>
                                                            </tr>
                                                        </table>
                                                    @else
                                                        <table class="table-bordered"
                                                               style="width: {{ $pavimento['largura'] }}%;
                                                                       margin:0px auto;min-height: 31px;">
                                                            <tr>
                                                                @foreach($pavimento['itens'] as $trechoIndex => $trecho)
                                                                    @if(isset($previsoes[$trecho['blocoId']]))
                                                                        @if(isset($medicoes[$previsoes[$trecho['blocoId']]->id]) )
                                                                            @if( $previsoes[$trecho['blocoId']]->qtd <= $medicoes[$previsoes[$trecho['blocoId']]->id]->qtd)
                                                                                {{--Foi medido 100%--}}
                                                                                <td class="trechoMedido100porcento" id="trecho{{ $trecho['blocoId'] }}">
                                                                            @else
                                                                                <td class="trechoMedido" id="trecho{{ $trecho['blocoId'] }}"
                                                                                    onclick="selecionaTrecho({{ $trecho['blocoId'] }});">
                                                                            @endif
                                                                        @else
                                                                            <td class="trecho" id="trecho{{ $trecho['blocoId'] }}"
                                                                                onclick="selecionaTrecho({{ $trecho['blocoId'] }});">
                                                                        @endif
                                                                            &nbsp;
                                                                            {{ $trecho['nome'] }}    &nbsp;
                                                                                @if(isset($medicoes[$previsoes[$trecho['blocoId']]->id]) )
                                                                                    @if( $previsoes[$trecho['blocoId']]->qtd <= $medicoes[$previsoes[$trecho['blocoId']]->id]->qtd )
                                                                                        100%
                                                                                        <div class="progress progress-xs">
                                                                                            <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                                                                                <span class="sr-only">100%</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        <?php
                                                                                            $porcent_number = number_format( ( ($medicoes[$previsoes[$trecho['blocoId']]->id]->qtd/$previsoes[$trecho['blocoId']]->qtd) * 100),0,'.','');
                                                                                            $porcent = number_format( ( ($medicoes[$previsoes[$trecho['blocoId']]->id]->qtd/$previsoes[$trecho['blocoId']]->qtd) * 100),2,',','.');
                                                                                        ?>
                                                                                            {{ $porcent  }}%
                                                                                    <div class="progress progress-xs active">
                                                                                        <div class="progress-bar progress-bar-red progress-bar-striped" role="progressbar" aria-valuenow="{{$porcent_number}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$porcent_number}}%">
                                                                                            <span class="sr-only">{{$porcent}}%</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                @else
                                                                                    0%
                                                                                @endif
                                                                        </td>
                                                                    @else
                                                                        <td class="trechoVazio"> &nbsp;{{ $trecho['nome'] }}&nbsp;</td>
                                                                    @endif
                                                                @endforeach
                                                            </tr>
                                                        </table>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
            @if($medicaoServico->medicoes()->count())
                <div class="row">
                    <div class="col-md-12 text-center">
                        {!! Form::model($medicaoServico, ['route' => ['medicaoServicos.update',
                                        $medicaoServico->id], 'method' => 'patch']) !!}
                        {!! Form::hidden('periodo_inicio', $medicaoServico->periodo_inicio) !!}

                        {!! Form::hidden('periodo_termino', $medicaoServico->periodo_termino) !!}

                        {!! Form::button( '<i class="fa fa-check"></i> Salvar e Enviar para Aprovação', [
                                        'class' => 'btn btn-success btn-lg btn-flat',
                                        'value'=>'1',
                                        'name'=>'finalizado',
                                        'type'=>'submit']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            @endif
        @endif

        @if( !is_null($medicaoServico) && !is_null($mcMedicaoPrevisao))
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        {!! Form::open(['route' => 'medicoes.store','files'=>true]) !!}

                            @include('medicoes.fields')

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @elseif( is_null($medicaoServico) && is_null($mcMedicaoPrevisao))
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                            {!! Form::open(['route' => 'medicao_servicos.store']) !!}

                            @include('medicao_servicos.fields')

                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    @if($medicaoServico)
    var previsoes = {!! json_encode($previsoes->toArray()) !!};
    function selecionaTrecho(qual){
        document.location = '{!! route('medicoes.create').
                    '?contrato_item_apropriacao_id='.$contratoItemApropriacao->id.
                    '&medicao_servico_id='.$medicaoServico->id.
                    '&mc_medicao_previsao_id=' !!}'+previsoes[qual].id;
    }
    @endif
</script>

@stop
