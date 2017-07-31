<div class="form-group col-sm-12">
    <table class="table table-hover table-bordered table-condensed table-striped" id="medicoes">
        <thead>
        <tr class="active">
            <th width="5%">
                Medição
            </th>
            <th>
                Insumo
            </th>
            <th width="5%">
                Unidade
            </th>
            <th>
                Quantidade Total do insumo
            </th>
            <th>
                Valor Unitário
            </th>
            <th>
                Valor Total
            </th>
            <th>
                Quantidade Medida
            </th>
            <th>
                Descontos
            </th>
            <th width="10%">
                Valor Medido - Descontos
            </th>
            <th width="10%">
                Saldo
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $somaTotal = 0;
        $somaDescontos = 0;
        $somaTotalVlr = 0;
        ?>
        @if(isset($medicaoBoletim))

            @foreach($medicaoBoletim->medicaoServicos as $medicaoServico)
                <tr id="medicaoServico{{ $medicaoServico->id }}">
                    <td>   {{ $medicaoServico->id }}
                        <input type="hidden" name="medicaoServicos[]" value="{{ $medicaoServico->id }}">
                    </td>
                    <td class="text-left">
                        {{ $medicaoServico->contratoItemApropriacao->insumo->codigo . ' - '.  $medicaoServico->contratoItemApropriacao->insumo->nome }}
                    </td>
                    <td>
                        {{ $medicaoServico->contratoItemApropriacao->insumo->unidade_sigla }}
                    </td>
                    <td class="text-right">
                        {{ float_to_money($medicaoServico->contratoItemApropriacao->contratoItem->qtd,'') }}
                    </td>
                    <td class="text-right">
                        {{ float_to_money($medicaoServico->contratoItemApropriacao->contratoItem->valor_unitario) }}
                    </td>
                    <td class="text-right">
                        {{ float_to_money($medicaoServico->contratoItemApropriacao->contratoItem->valor_total) }}
                    </td>
                    <td class="text-right">
                        {{ float_to_money($medicaoServico->medicoes()->sum('qtd'),'') }}
                    </td>
                    <td class="text-right">
                        {{ float_to_money($medicaoServico->descontos,'') }}
                    </td>
                    <td class="text-right">
                        <?php
                        $valorItem = $medicaoServico->medicoes()->sum('qtd')* $medicaoServico->contratoItemApropriacao->contratoItem->valor_unitario;
                        $somaTotal += $valorItem;
                        $somaDescontos += $medicaoServico->descontos;

                        $somaTotalVlr += $medicaoServico->contratoItemApropriacao->contratoItem->valor_total;

                        $contratoItem = $medicaoServico->contratoItemApropriacao->contratoItem;
                        $valoresJaMedidos = $contratoItem->contratoItemReapropriacao()->select([
                                'contrato_item_apropriacoes.insumo_id',
                                'medicao_servicos.descontos',
                                \Illuminate\Support\Facades\DB::raw('( SELECT SUM(qtd) FROM medicoes WHERE medicao_servico_id = medicao_servicos.id ) as qtd_medida')
                        ])
                                ->join('medicao_servicos','medicao_servicos.contrato_item_apropriacao_id','contrato_item_apropriacoes.id')
                                ->whereExists(function ($query2) {
                                    $query2->select(DB::raw(1))
                                            ->from('medicao_boletim_medicao_servico')
                                            ->join('medicao_boletins','medicao_boletins.id','medicao_boletim_medicao_servico.medicao_boletim_id')
                                            ->whereRaw('medicao_boletim_medicao_servico.medicao_servico_id = medicao_servicos.id')
                                            ->where('medicao_boletins.medicao_boletim_status_id','>','1');
                                })
                                ->get()->toArray();
                        $jaMedido = 0;
                        if(count($valoresJaMedidos)){
                            foreach ($valoresJaMedidos as $valorJaMedido) {
                                $jaMedido += ($valorJaMedido['qtd_medida'] * $medicaoServico->contratoItemApropriacao->contratoItem->valor_unitario)-$valorJaMedido['descontos'];
                            }
                        }


                        $saldo = $medicaoServico->contratoItemApropriacao->contratoItem->valor_total - $jaMedido - ($valorItem -$medicaoServico->descontos)  ;

                        ?>
                        {{ float_to_money($valorItem-$medicaoServico->descontos) }}
                    </td>
                    <td class="text-right">
                        {{ float_to_money($saldo) }}
                    </td>
                </tr>

            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr class="warning">
            <td colspan="7" class="text-right">
                Total:
            </td>
            <td class="text-right" style="font-weight: bold !important; font-size: 16px;" id="somaDescontos">
                @if(isset($medicaoBoletim))
                    {{ float_to_money($somaDescontos) }}
                @endif
            </td>
            <td class="text-right" style="font-weight: bold !important; font-size: 16px;" id="somaTotal">
                @if(isset($medicaoBoletim))
                    {{ float_to_money($somaTotal-$somaDescontos) }}
                @endif
            </td>
            <td>

            </td>
        </tr>
        </tfoot>

    </table>
</div>