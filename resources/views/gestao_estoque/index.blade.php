@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Gestão de estoque
        </h1>

        <!-- Nome Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('visao', 'Visão:') !!}
            <div class="btn-group">
                <button onclick="mudaVisao('E');" id="visao_E" class="btn btn-warning" style="width:150px;">Estoque</button>
                <button onclick="mudaVisao('P');" id="visao_P" class="btn btn-success" style="width:150px;">Perda</button>
                <button onclick="mudaVisao('C');" id="visao_C" class="btn btn-primary" style="width:150px;">Contrato</button>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="fixTable" class="table table-bordered table-striped table-condensed table-nowrap">
                    <thead>
                    <tr align="center">
                        <th id="td_1" class="class_visao_E" colspan="1" style="background-color: white;"></th>
                        <th id="td_2" class="class_visao_P" colspan="1" style="background-color: white;"></th>
                        <th id="td_3" class="class_visao_E" colspan="6" style="background-color: white;"></th>
                        <th id="td_4" class="class_visao_P" colspan="3" style="text-align: center;">Em andamento</th>
                        <th id="td_5" class="class_visao_P" colspan="2" style="background-color: white;"></th>
                        <th id="td_6" class="class_visao_P" colspan="4" style="text-align: center;">Perda</th>
                        <th id="td_7" class="class_visao_P" colspan="1" style="background-color: white;"></th>
                        <th id="td_8" class="class_visao_C" colspan="5" style="text-align: center;">Contrato</th>
                    </tr>
                    <tr align="left">
                        <th class="class_visao_E">Obra</th>
                        <th class="class_visao_P">Local</th>
                        <th class="class_visao_E">Cód</th>
                        <th class="class_visao_E">Insumo</th>
                        <th class="class_visao_E">Un de medida</th>
                        <th class="class_visao_E">Em estoque</th>
                        <th class="class_visao_P">Previsto</th>
                        <th class="class_visao_P">Aplicado</th>

                        <th class="class_visao_P" nowrap>Solicitado</th>
                        <th class="class_visao_P" nowrap>Em preparo</th>
                        <th class="class_visao_P" nowrap>Em Trânsito</th>

                        <th class="class_visao_P" nowrap>Tranferência</th>
                        <th class="class_visao_P" nowrap>Empréstimo</th>

                        <th class="class_visao_P" nowrap>Prevista</th>
                        <th class="class_visao_P" nowrap>Real / Projeção</th>
                        <th class="class_visao_P" nowrap>Concluído</th>
                        <th class="class_visao_P" nowrap>Farol</th>

                        <th class="class_visao_P" nowrap>Evolução Física</th>

                        <th class="class_visao_C" nowrap>Contratado</th>
                        <th class="class_visao_C" nowrap>Realizado</th>
                        <th class="class_visao_C" nowrap>Saldo</th>
                        <th class="class_visao_C" nowrap>Projeção de término</th>
                        <th class="class_visao_C" nowrap>Ajustes</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($estoque as $item)
                            <tr align="left">
                                <td class="class_visao_E">{{$item->obra->nome}}</td>
                                <td class="class_visao_P"></td>
                                <td class="class_visao_E">{{$item->insumo->codigo}}</td>
                                <td class="class_visao_E">{{$item->insumo->nome}}</td>
                                <td class="class_visao_E">{{$item->insumo->unidade_sigla}}</td>
                                @if($item->farolQtdEmEstoque()['porcentagem'])
                                    @if($item->farolQtdEmEstoque()['cor'])
                                        <td class="class_visao_E" style="background-color:{{$item->farolQtdEmEstoque()['cor']}}">
                                            <span data-toggle="tooltip"
                                                  title="Atenção, {{float_to_money($item->farolQtdEmEstoque()['porcentagem'], '')}}%  da quantidade mínima."
                                            {{$item->farolQtdEmEstoque()['cor'] == '#000000' ? 'style=color:white' : ''}}>
                                                {{float_to_money($item->qtdEmEstoque(), '')}}
                                            </span>
                                        </td>
                                    @else
                                        <td class="class_visao_E">{{float_to_money($item->qtdEmEstoque(), '')}}</td>
                                    @endif
                                @else
                                    <td class="class_visao_E">
                                        {{float_to_money($item->qtdEmEstoque(), '')}}
                                        <i title="Inserir quantidade mínima" data-toggle="tooltip" class="fa fa-info-circle text-info" style="font-size: 20px;"></i>
                                    </td>
                                @endif
                                <td class="class_visao_P">{{float_to_money($item->qtdPrevista(), '')}}</td>
                                <td class="class_visao_P">{{float_to_money($item->qtdAplicada(), '')}}</td>

                                <td class="class_visao_P" nowrap>{{float_to_money($item->qtdRequisitada(), '')}}</td>
                                <td class="class_visao_P" nowrap>{{float_to_money($item->qtdEmSeparacao(), '')}}</td>
                                <td class="class_visao_P" nowrap>{{float_to_money($item->qtdEmTransito(), '')}}</td>

                                <td class="class_visao_P" nowrap></td>
                                <td class="class_visao_P" nowrap></td>

                                <td class="class_visao_P" nowrap></td>
                                <td class="class_visao_P" nowrap></td>
                                <td class="class_visao_P" nowrap></td>
                                <td class="class_visao_P" nowrap></td>

                                <td class="class_visao_P" nowrap></td>

                                <td class="class_visao_C" nowrap>{{float_to_money($item->qtdContratada(), '')}}</td>
                                <td class="class_visao_C" nowrap>{{float_to_money($item->qtdRealizada(), '')}}</td>
                                <td class="class_visao_C" nowrap>{{float_to_money( ($item->qtdContratada() - $item->qtdRealizada()), '')}}</td>
                                <td class="class_visao_C" nowrap></td>
                                <td class="class_visao_C" nowrap></td>
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

        function mudaVisao($visao) {
            if($visao == 'E') {
                $class = 'btn btn-warning';
            } else if($visao == 'P') {
                $class = 'btn btn-success';
            } else {
                $class = 'btn btn-primary';
            }

            if($('#visao_'+$visao).attr('class') == 'btn btn-default') {
                $('#visao_'+$visao).attr('class', $class);
                $('.class_visao_'+$visao).show();

                if($visao == 'E') {
                    $('#td_2').attr('colspan', '1');
                } else if($visao == 'P') {
                    $('#td_3').attr('colspan', '6');
                }

            } else {
                $('#visao_'+$visao).attr('class', 'btn btn-default');
                $('.class_visao_'+$visao).hide();

                if($visao == 'E') {
                    $('#td_2').attr('colspan', '3');
                } else if($visao == 'P') {
                    $('#td_3').attr('colspan', '4');
                }
            }
        }
    </script>
@endsection