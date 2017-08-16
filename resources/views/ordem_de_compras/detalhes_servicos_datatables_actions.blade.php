<label class="switch">
    @php $array_replace = [
                            '<small class="pull-left">R$</small>',
                            '<span style="color: #7ed321">',
                            '<span style="color: #eb0000">',
                            '</span>'
                          ];
    @endphp
    <input type="checkbox" class="detalhes_servicos_itens"
           id="{{$id}}"
           valor_previsto="{{$valor_previsto}}"
           valor_comprometido_a_gastar="{{money_to_float(str_replace($array_replace, '', $valor_comprometido_a_gastar).'')}}"
           saldo_orcamento="{{$saldo_orcamento}}"
           valor_oc="{{money_to_float(str_replace($array_replace, '', $valor_oc).'')}}"
           saldo_disponivel="{{money_to_float(str_replace($array_replace, '', $saldo_disponivel).'')}}"
    onchange="recalcularAnaliseServico();">
    <span class="slider round"></span>
</label>