@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Gestão de estoque
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="fixTable" class="table table-bordered table-striped table-condensed table-nowrap">
                    <thead>
                    <tr align="center">
                        <th colspan="8" style="background-color: white;"></th>
                        <th colspan="3" style="text-align: center;">Em adamento</th>
                        <th colspan="2" style="background-color: white;"></th>
                        <th colspan="4" style="text-align: center;">Perda</th>
                        <th colspan="1" style="background-color: white;"></th>
                        <th colspan="5" style="text-align: center;">Contrato</th>
                    </tr>
                    <tr align="left">
                        <th>Obra</th>
                        <th>Local</th>
                        <th>Cód</th>
                        <th>Insumo</th>
                        <th>Un de medida</th>
                        <th>Em estoque</th>
                        <th>Previsto</th>
                        <th>Aplicado</th>

                        <th nowrap>Solicitado</th>
                        <th nowrap>Em preparo</th>
                        <th nowrap>Em Trânsito</th>

                        <th nowrap>Tranferência</th>
                        <th nowrap>Empréstimo</th>

                        <th nowrap>Prevista</th>
                        <th nowrap>Real / Projeção</th>
                        <th nowrap>Concluído</th>
                        <th nowrap>Farol</th>

                        <th nowrap>Evolução Física</th>

                        <th nowrap>Contratado</th>
                        <th nowrap>Realizado</th>
                        <th nowrap>Saldo</th>
                        <th nowrap>Projeção de término</th>
                        <th nowrap>Ajustes</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($estoque as $item)
                            <tr align="left">
                                <td>{{$item->obra->nome}}</td>
                                <td></td>
                                <td>{{$item->insumo->codigo}}</td>
                                <td>{{$item->insumo->nome}}</td>
                                <td>{{$item->insumo->unidade_sigla}}</td>
                                <td>{{float_to_money($item->qtdEmEstoque(), '')}}</td>
                                <td>{{float_to_money($item->qtdPrevista(), '')}}</td>
                                <td>{{float_to_money($item->qtdAplicada(), '')}}</td>

                                <td nowrap>{{float_to_money($item->qtdRequisitada(), '')}}</td>
                                <td nowrap>{{float_to_money($item->qtdEmSeparacao(), '')}}</td>
                                <td nowrap>{{float_to_money($item->qtdEmTransito(), '')}}</td>

                                <td nowrap></td>
                                <td nowrap></td>

                                <td nowrap></td>
                                <td nowrap></td>
                                <td nowrap></td>
                                <td nowrap></td>

                                <td nowrap></td>

                                <td nowrap>{{float_to_money($item->qtdContratada(), '')}}</td>
                                <td nowrap>{{float_to_money($item->qtdRealizada(), '')}}</td>
                                <td nowrap>{{float_to_money( ($item->qtdContratada() - $item->qtdRealizada()), '')}}</td>
                                <td nowrap></td>
                                <td nowrap></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $("#fixTable").tableHeadFixer({'left' : 8, 'head' : true});
        });
    </script>
@endsection