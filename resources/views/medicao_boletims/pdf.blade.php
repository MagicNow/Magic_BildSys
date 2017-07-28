@extends('layouts.printable')

@section('content')
    <div class="row">
        <div class="form-group col-xs-5">
            {!! Form::label('obra', 'Obra:') !!}
            <div class="form-control">
                {{ $medicaoBoletim->obra->nome }}
            </div>
        </div>

        <!-- Contrato Id Field -->
        <div class="form-group col-xs-6">
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
                <tr>
                    <th colspan="2">

                    </th>
                    <th colspan="3" class="text-center">
                        CONTRATADO
                    </th>
                    <th colspan="2" class="text-center">
                        QUANTIDADE
                    </th>
                    <th colspan="3" class="text-center">
                        VALOR
                    </th>
                </tr>
                <tr class="active">
                    <th  class="text-center">
                        Insumo
                    </th>

                    <th width="5%" class="text-center">
                        Unidade
                    </th>
                    <th class="text-center">
                        Quantidade
                    </th>
                    <th class="text-center">
                        Valor Unitário
                    </th>
                    <th class="text-center">
                        Valor Total
                    </th>
                    <th class="text-center">
                        Medida
                    </th>

                    <th width="10%" class="text-center">
                        Saldo
                    </th>
                    <th width="10%" class="text-center">
                        Descontos
                    </th>
                    <th width="10%" class="text-center">
                        Medido - Descontos
                    </th>
                    <th width="10%" class="text-center">
                        Saldo
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $somaTotal = 0;
                $somaDescontos = 0;
                $somaTotalVlr = 0;
                $somaTotalVlrContratado = 0;
                $somaTotalVlrSaldo = 0;
                $insumos_medidos =[];
                ?>

                    @foreach($insumosMedidos as $medicaoServico)
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
                        $jaMedido_qtd = 0;
                        if(count($valoresJaMedidos)){
                            foreach ($valoresJaMedidos as $valorJaMedido) {
                                $jaMedido += ($valorJaMedido['qtd_medida'] * $medicaoServico->valor_unitario)-$valorJaMedido['descontos'];
                                $jaMedido_qtd += $valorJaMedido['qtd_medida'] ;
                            }
                        }


                        $saldo = $medicaoServico->valor_total - $jaMedido - ($valorItem -$medicaoServico->descontos);
                        $saldo_qtd = $medicaoServico->qtd - $jaMedido_qtd - ($medicaoServico->qtd_medida);


                        $somaTotalVlrContratado += $medicaoServico->valor_total;
                        $somaTotalVlrSaldo += $saldo;

                        ?>
                        <tr>
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
                                {{ float_to_money($saldo_qtd,'') }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($medicaoServico->descontos,'') }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($valorItem-$medicaoServico->descontos) }}
                            </td>
                            <td class="text-right">
                                {{ float_to_money($saldo) }}
                            </td>

                        </tr>

                    @endforeach

                @foreach($insumosNaoMedidos as $medicaoServico)
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
                    $jaMedido_qtd = 0;
                    if(count($valoresJaMedidos)){
                        foreach ($valoresJaMedidos as $valorJaMedido) {
                            $jaMedido += ($valorJaMedido['qtd_medida'] * $medicaoServico->valor_unitario)-$valorJaMedido['descontos'];
                            $jaMedido_qtd += $valorJaMedido['qtd_medida'] ;
                        }
                    }


                    $saldo = $medicaoServico->valor_total - $jaMedido - ($valorItem -$medicaoServico->descontos)  ;
                    $saldo_qtd = $medicaoServico->qtd - $jaMedido_qtd - ($medicaoServico->qtd_medida)  ;


                    $somaTotalVlrContratado += $medicaoServico->valor_total;
                    $somaTotalVlrSaldo += $saldo;
                    ?>
                    <tr>
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
                            {{ float_to_money($saldo_qtd,'') }}
                        </td>
                        <td class="text-right">
                            {{ float_to_money($medicaoServico->descontos,'') }}
                        </td>
                        <td class="text-right">
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
                    <td colspan="4" class="text-right">
                        Total:
                    </td>
                    <td class="text-right" style="font-size: 16px;">
                            {{ float_to_money($somaTotalVlrContratado) }}
                    </td>
                    <td colspan="2">

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
                    <td colspan="2" class="text-right" style="font-size: 16px;">
                        {{ float_to_money($somaTotalVlrSaldo) }}
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
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Observações</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-hover table-bordered table-condensed table-striped" id="medicoes">
                <tr class="active">
                    <th class="text-right" width="5%">
                        Medição
                    </th>
                    <th class="text-left" width="60%">
                        Insumo
                    </th>
                    <th class="text-right" width="30%">
                        Quantidade
                    </th>
                </tr>
                <tbody>
                <?php
                $somaTotal = 0;
                ?>
                @if(isset($medicaoBoletim))
                    @foreach($medicaoBoletim->medicaoServicos as $medicaoServico)
                        <tr id="medicaoServico{{ $medicaoServico->id }}">
                            <td class="text-right" >   {{ $medicaoServico->id }}
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
                                {{ float_to_money($medicaoServico->medicoes()->sum('qtd'),'') }}
                            </td>
                        </tr>
                        @if( $medicaoServico->medicoes()->count() )
                            <tr>
                                {{--<td class="text-right" >--}}
                                   {{--<span class="badge">--}}
                                       {{--{{ $medicaoServico->medicoes()->count() }}--}}
                                   {{--</span><br> Trechos--}}
                                {{--</td>--}}
                                <td colspan="3">
                                    <table class="table table-no-margin table-bordered table-hover table-condensed table-striped">
                                        <tr>
                                            <th class="text-left">
                                                Local
                                            </th>
                                            <th class="text-right">
                                                Quantidade
                                            </th>
                                            <th class="text-right">
                                                Percentual
                                            </th>
                                            <th class="text-left">
                                                Observação
                                            </th>
                                            <th class="text-center">
                                                Data da Medição
                                            </th>
                                            <th class="text-left">
                                                Responsável pela medição
                                            </th>
                                        </tr>
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
                                                <td class="text-right">
                                                    {{ float_to_money($medicao->qtd,'') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format( ( ($medicao->qtd/$medicao->mcMedicaoPrevisao->qtd)*100 ),2,',','.') }} %
                                                </td>
                                                <td>
                                                    {{ $medicao->obs }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $medicao->created_at->format('d/m/Y') }}
                                                </td>

                                                <td>
                                                    {{ $medicao->user->name }}
                                                </td>
                                            </tr>
                                            @if($medicao->medicaoImagens()->count())
                                           <tr>
                                               <td colspan="6">
                                                   @foreach($medicao->medicaoImagens as $imagem)
                                                        @if(is_file(storage_path('app/'.$imagem->imagem)))
                                                            <span>
                                                               <img height="300"
                                                                    src="{!! url('/imagem?file='.$imagem->imagem.'&mode=resize&height=300') !!}">
                                                            </span>

                                                       @endif
                                                   @endforeach
                                               </td>
                                           </tr>
                                            @endif

                                        @endforeach

                                    </table>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
                {{--<tfoot>
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
                </tfoot>--}}

            </table>
        </div>
    </div>


    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Ciência</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            {{ \App\Models\ConfiguracaoEstatica::find(3)->valor }}
            <div class="row" style="padding-top: 30px">

                <div class="col-xs-6">

                    ___________________________________<br>
                    Contratada
                </div>
                <div class="col-xs-6">

                    ___________________________________<br>
                    Gestor da Obra
                </div>
            </div>
        </div>
    </div>
@stop