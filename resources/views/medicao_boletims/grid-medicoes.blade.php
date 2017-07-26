<div class="form-group col-sm-12">
    <table class="table table-hover table-bordered table-condensed table-striped" id="medicoes">
        <thead>
        <tr class="active">
            <th width="10%">
                Medição
            </th>
            <th width="60%">
                Insumo
            </th>
            <th width="30%">
                Valor
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $somaTotal = 0;
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
                    <td class="text-right">
                        <?php
                        $valorItem = $medicaoServico->medicoes()->sum('qtd')* $medicaoServico->contratoItemApropriacao->contratoItem->valor_unitario;
                        $somaTotal += $valorItem;
                        ?>
                        {{ float_to_money($valorItem) }}
                    </td>
                </tr>
                @if( $medicaoServico->medicoes()->count() )
                    <tr>
                        <td>
                           <span class="badge">
                               {{ $medicaoServico->medicoes()->count() }}
                           </span> Trechos/Blocos
                        </td>
                        <td colspan="3">
                            <table class="table table-no-margin table-bordered table-hover table-condensed table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        Local
                                    </th>
                                    <th>
                                        Quantidade
                                    </th>
                                    <th>
                                        Percentual
                                    </th>
                                    <th>
                                        Responsável
                                    </th>
                                    <th>
                                        Observação
                                    </th>
                                    <th>
                                        Data da Medição
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $medicoes = $medicaoServico->medicoes()
                                        ->with( 'mcMedicaoPrevisao',
                                                'mcMedicaoPrevisao.memoriaCalculoBloco',
                                                'mcMedicaoPrevisao.memoriaCalculoBloco.estruturaObj',
                                                'mcMedicaoPrevisao.memoriaCalculoBloco.pavimentoObj',
                                                'mcMedicaoPrevisao.memoriaCalculoBloco.trechoObj')
                                        ->get();
                                ?>
                                @foreach($medicaoServico->medicoes as $medicao)
                                    <tr>
                                        <td>
                                            {{ $medicao->mcMedicaoPrevisao->memoriaCalculoBloco->estruturaObj->nome }} -
                                            {{ $medicao->mcMedicaoPrevisao->memoriaCalculoBloco->pavimentoObj->nome }} -
                                            {{ $medicao->mcMedicaoPrevisao->memoriaCalculoBloco->trechoObj->nome }}
                                        </td>
                                        <td>
                                            {{ float_to_money($medicao->qtd,'') }}
                                        </td>
                                        <td>
                                            {{ number_format( ( ($medicao->qtd/$medicao->mcMedicaoPrevisao->qtd)*100 ),2,',','.') }} %
                                        </td>
                                        <td>
                                            {{ $medicao->user->name }}
                                        </td>
                                        <td>
                                            {{ $medicao->obs }}
                                        </td>
                                        <td>
                                            {{ $medicao->created_at->format('d/m/Y') }}
                                        </td>

                                    </tr>


                                @endforeach

                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr class="warning">
            <td colspan="2" class="text-right">
                Total:
            </td>
            <td class="text-right" style="font-weight: bold !important; font-size: 16px;" id="somaTotal">
                @if(isset($medicaoBoletim))
                    {{ float_to_money($somaTotal) }}
                @endif
            </td>
            <td>

            </td>
        </tr>
        </tfoot>

    </table>
</div>