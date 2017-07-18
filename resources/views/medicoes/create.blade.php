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
            border: 2px solid #6e2423 !important;
        }
        .trechoMedido100porcento{
            border: 2px solid #000 !important;
        }
    </style>
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Nova Medicão
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')

        <div class="box box-default">
            <div class="box-body">
                <h4>Obra: {{ $contratoItemApropriacao->contratoItem->contrato->obra->nome }}</h4>
                <h4>
                    Contrato: {{ $contratoItemApropriacao->contratoItem->contrato->id }}
                    {{ $contratoItemApropriacao->contratoItem->contrato->fornecedor->nome }}
                </h4>
                <h4>Insumo: {{ $contratoItemApropriacao->codigo_insumo }} - {{ $contratoItemApropriacao->insumo->nome }}</h4>
            </div>
        </div>
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
                                                                            @if( $medicoes[$previsoes[$trecho['blocoId']]->id]->qtd == $previsoes[$trecho['blocoId']]->qtd)
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
                                                                            {{ $trecho['nome'] }}
                                                                            &nbsp;
                                                                        </td>
                                                                    @else
                                                                        <td> &nbsp;{{ $trecho['nome'] }}&nbsp;</td>
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

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'medicoes.store']) !!}

                        @include('medicoes.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    var previsoes = {!! json_encode($previsoes->toArray()) !!};
    function selecionaTrecho(qual){
        console.log(qual, previsoes[qual]);
    }
</script>

@stop