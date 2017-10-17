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
                <a href="{{route('gestaoEstoque.estoqueMinimo')}}?obra_id={{$item->obra->id}}&insumo_id={{$item->insumo->id}}">
                    <i title="Inserir quantidade mínima" data-toggle="tooltip" class="fa fa-info-circle text-info"
                       style="font-size: 20px;"></i>
                </a>
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