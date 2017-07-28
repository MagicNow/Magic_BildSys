@extends('layouts.printable')

@section('content')
    <div class="row">
        <div class="form-group col-sm-6">
            {!! Form::label('obra', 'Obra:') !!}
            <div class="form-control">
                {{ $medicaoBoletim->obra->nome }}
            </div>
        </div>

        <!-- Contrato Id Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('contrato_id', 'Contrato:') !!}
            <div class="form-control">
                {{ $medicaoBoletim->contrato_id . ' - '. $medicaoBoletim->contrato->fornecedor->nome }}
            </div>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Contrato</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-hover table-bordered table-condensed table-striped" id="medicoes">
                <thead>
                <tr class="active">
                    <th width="5%">
                        Insumo
                    </th>
                    <th>

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
                    <th width="10%">
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
                        $insumos_medidos =[];
                ?>

                    @foreach($insumosMedidos as $medicaoServico)
                        <tr>
                            <td>
                                {{ $medicaoServico->codigo  }}
                            </td>
                            <td class="text-left">
                                {{ $medicaoServico->nome }}
                            </td>
                            <td>
                                {{ $medicaoServico->unidade_sigla }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($medicaoServico->qtd,'') }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($medicaoServico->valor_unitario) }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($medicaoServico->valor_total) }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($medicaoServico->qtd_medida,'') }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($medicaoServico->descontos,'') }}
                            </td>
                            <td class="text-right">
                                <?php
                                $insumos_medidos[] = $medicaoServico->insumo_id;
                                $valorItem = $medicaoServico->qtd_medida * $medicaoServico->valor_unitario;
                                $somaTotal += $valorItem;
                                $somaDescontos += $medicaoServico->descontos;

                                $somaTotalVlr += $medicaoServico->valor_total;

                                $contratoItem = \App\Models\ContratoItem::find($medicaoServico->id);
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
                                        $jaMedido += ($valorJaMedido['qtd_medida'] * $medicaoServico->valor_unitario)-$valorJaMedido['descontos'];
                                    }
                                }


                                $saldo = $medicaoServico->valor_total - $jaMedido - ($valorItem -$medicaoServico->descontos)  ;

                                ?>
                                {{ float_to_money($valorItem-$medicaoServico->descontos) }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($saldo) }}
                            </td>
                        </tr>

                    @endforeach

                @foreach($insumosNaoMedidos as $medicaoServico)
                    <tr>
                        <td>
                            {{ $medicaoServico->codigo  }}
                        </td>
                        <td class="text-left">
                            {{ $medicaoServico->nome }}
                        </td>
                        <td>
                            {{ $medicaoServico->unidade_sigla }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($medicaoServico->qtd,'') }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($medicaoServico->valor_unitario) }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($medicaoServico->valor_total) }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($medicaoServico->qtd_medida,'') }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($medicaoServico->descontos,'') }}
                        </td>
                        <td class="text-right">
                            <?php
                            $insumos_medidos[] = $medicaoServico->insumo_id;
                            $valorItem = $medicaoServico->qtd_medida * $medicaoServico->valor_unitario;
                            $somaTotal += $valorItem;
                            $somaDescontos += $medicaoServico->descontos;

                            $somaTotalVlr += $medicaoServico->valor_total;

                            $contratoItem = \App\Models\ContratoItem::find($medicaoServico->id);
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
                                    $jaMedido += ($valorJaMedido['qtd_medida'] * $medicaoServico->valor_unitario)-$valorJaMedido['descontos'];
                                }
                            }


                            $saldo = $medicaoServico->valor_total - $jaMedido - ($valorItem -$medicaoServico->descontos)  ;

                            ?>
                            {{ float_to_money($valorItem-$medicaoServico->descontos) }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($saldo) }}
                        </td>
                    </tr>

                @endforeach
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
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Custo por funcionário</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-hover table-bordered table-condensed table-striped">
                <thead>
                    <tr>
                        <th title="é a soma de todos os funcionário apontados nas medições">Nº de Oficiais</th>
                        <th title="é a soma de todos os funcionário apontados nas medições">Nº de ajudantes</th>
                        <th title="é a soma de todos os funcionário apontados nas medições">Nº de outros</th>
                        <th title="Soma todos os colaboradores do fornecedor aprontado nas medições">Total de funcionários</th>
                        <th>R$ / Funcionário</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $qtd_oficiais = 0;
                    $qtd_ajudantes = 0;
                    $qtd_outros = 0;
                ?>
                    @foreach($medicaoBoletim->medicaoServicos as $medicaoServico)
                        <?php
                        $qtd_oficiais += $medicaoServico->qtd_funcionarios;
                        $qtd_ajudantes += $medicaoServico->qtd_ajudantes;
                        $qtd_outros += $medicaoServico->outros;
                        ?>
                    @endforeach
                    <tr>
                        <td>{{ $qtd_oficiais }}</td>
                        <td>{{ $qtd_ajudantes }}</td>
                        <td>{{ $qtd_outros }}</td>
                        <td>{{ ($qtd_oficiais+$qtd_ajudantes+$qtd_outros) }}</td>
                        <td>{{ float_to_money(( ($somaTotal-$somaDescontos)  / ($qtd_oficiais+$qtd_ajudantes+$qtd_outros))) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    Observações
    Observações	"Neste campo deve apercer todas as observações apontadas nas medições, seja por trecho ou por medição.
    Bem como deve possuir anexo ao boletim de medição um espelho da memória de cálculo com todos as informações da medição, até os anexos"

    <div class="row">
        <hr>
        Declaro concordar com os valores e quantidades constantes nesta medição, não restando nada a medir até esta data, e  estar ciente  da necessidade do envio da documentação descriminada em contrato para a liberação desta medição.
    </div>
@stop