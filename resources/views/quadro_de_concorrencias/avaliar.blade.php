@extends('layouts.front')

@section('content')
    <style type="text/css">
        /*.table-nowrap>tr>td{*/
            /*white-space: nowrap;*/
        /*}*/
    </style>
    <div class="row">
        <div class="col-sm-12">
            <section class="content-header">
                <h1 class="pull-left">
                    <button type="button" class="btn btn-link" onclick="history.go(-1);">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </button>
                    @if($quadro->qc_status_id == 7)
                    Avaliar
                    @else
                    Histórico do
                    @endif
                    Quadro de Concorrência
                    <small>
                        Rodada {{ $rodadaSelecionada }}
                    </small>
                </h1>
                @if($quadro->qc_status_id == 7)
                    <button class="btn btn-success btn-lg pull-right btn-flat"
                            data-toggle="modal"
                            data-target="#modal-finalizar">
                        <i class="fa fa-trophy" aria-hidden="true"></i>
                        Informar vencedor
                        ou
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        Gerar nova rodada
                    </button>
                @endif
            </section>
        </div>
    </div>
    <div class="content">

        {{-- Rodada --}}
        <div class="box box-muted" id="box_rodadas">
            <div class="box-header with-border">
                <i class="fa fa-refresh"></i>
                Exibir rodada
                <button type="button" class="btn btn-default btn-xs pull-right"
                        onclick="expandeEncolhe('box_rodadas');">
                    <i class="iconeExpandeEncolhe fa fa-minus"></i>
                </button>
            </div>
            <div class="box-body">
                @foreach(range(1, $quadro->rodada_atual) as $rodada)
                    <label class="radio-inline">
                        {!!
                          Form::radio(
                            'rodada',
                            $rodada, $rodada === $rodadaSelecionada,
                            [ 'class' => 'js-change-round' ]
                          )
                        !!}
                        Rodada {{ $rodada }} {{ $rodada === $quadro->rodada_atual ? '(atual)' : '' }}
                    </label>
                @endforeach
            </div>
        </div>

        {{--Preços dos insumos--}}
        <div class="box box-muted" id="box_precos">
            <div class="box-header with-border">
                <i class="fa fa-usd"></i>
                Preços
                <button type="button" class="btn btn-default btn-xs pull-right"
                        onclick="expandeEncolhe('box_precos');">
                    <i class="iconeExpandeEncolhe fa fa-minus"></i>
                </button>
            </div>
            <div class="box-body text-right">

                    <div class="table-responsive">
                        <table id="fixTable" class="table table-bordered table-striped table-condensed table-nowrap">
                            <thead>
                            <tr>
                                <th nowrap class="text-left"></th>
                                <th nowrap class="text-center"></th>
                                <th nowrap class="text-right"></th>
                                <th nowrap colspan="2"  class="text-center"></th>
                                @foreach($qcFornecedores as $qcFornecedor)
                                    <th colspan="2" nowrap class="text-center">
                                        {{ $qcFornecedor->fornecedor->nome }}
                                    </th>
                                @endforeach
                            </tr>

                            <tr>
                                <th>Insumo</th>
                                <th>Un. de medida</th>
                                <th>QTD. Q.C.</th>
                                <th nowrap class="text-right">Vlr. Unitário Orçamento</th>
                                <th nowrap class="text-right">Vlr. Total</th>
                                @foreach($qcFornecedores as $qcFornecedor)
                                    <th nowrap class="text-right">Vlr. Unitário</th>
                                    <th nowrap class="text-right">Vlr. Total</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                    $vl_total_orcamento = 0;
                            ?>
                            @foreach($itens as $item)
                                @if($item['insumo']!='TOTAL')
                                    <tr>
                                        <td nowrap class="text-left">{{ $item['insumo'] }}</td>
                                        <td nowrap class="text-center">{{ $item['unidade'] }}</td>
                                        <td nowrap class="text-right">{{ $item['qntd do QC'] }}</td>
                                        <td nowrap class="text-right">{{ $item['valor unitário do orçamento'] }}</td>
                                        <td nowrap class="text-right">{{ $item['Valor total previsto'] }}</td>
                                        <?php $vl_total_orcamento += doubleval($item['vl_total_orcamento']); ?>
                                        @foreach($qcFornecedores as $qcFornecedor)
                                            <td nowrap class="text-right">{{ isset($item[$qcFornecedor->id])? $item[$qcFornecedor->id]['unitario'] : '' }}</td>
                                            <td nowrap class="text-right">{{ isset($item[$qcFornecedor->id])? $item[$qcFornecedor->id]['total']: '' }}</td>
                                        @endforeach
                                    </tr>
                                @else
                                    <tr class="warning">
                                        <td colspan="4" class="text-left">TOTAL</td>
                                        <td class="text-right">{{ float_to_money($vl_total_orcamento) }}</td>
                                        @foreach($qcFornecedores as $qcFornecedor)
                                            <td nowrap colspan="2" class="text-right">{{ isset($item[$qcFornecedor->id])? $item[$qcFornecedor->id] : '' }}</td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                {{--{!!--}}
                  {{--$dataTable->table([--}}
                    {{--'width' => '100%',--}}
                    {{--'class' => 'table table-striped table-hover',--}}
                    {{--'style' => 'text-align: right;'--}}
                  {{--], true)--}}
                {{--!!}--}}
            </div>
        </div>

        {{-- Condições Comerciais --}}
        @if($campos_extras)
            <div class="box box-muted" id="box_campos_extras">
                <div class="box-header with-border">
                    <i class="fa fa-coffee"></i>
                    Condições Comerciais
                    <button type="button" class="btn btn-default btn-xs pull-right"
                            onclick="expandeEncolhe('box_campos_extras');">
                        <i class="iconeExpandeEncolhe fa fa-minus"></i>
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-condensed table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Condição</th>
                            @foreach($qcFornecedores as $qcFornecedor)
                                <th>
                                    {{ $qcFornecedor->fornecedor->nome }}
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($campos_extras as $campo_extra)
                            <tr>
                                <td class="text-left">
                                    {{ $campo_extra->nome }}
                                </td>
                                @php
                                    $v_tag = str_replace('[','', $campo_extra->tag);
                                    $v_tag = str_replace(']','', $v_tag);
                                    $classe_campo = 'text-left';
                                    if($campo_extra->tipo=='numero'){
                                        $classe_campo = 'text-right';
                                    }
                                @endphp
                                @foreach($qcFornecedores as $qcFornecedor)
                                @php
                                    $campo_extra_fornecedor = null;
                                    if(strlen($qcFornecedor->campos_extras_contrato)){
                                        $campos_extras_contrato = json_decode($qcFornecedor->campos_extras_contrato);
                                        $campo_extra_fornecedor = isset($campos_extras_contrato->$v_tag)?$campos_extras_contrato->$v_tag:'';
                                    }
                                @endphp
                                    <td class="{{ $campo_extra_fornecedor? $classe_campo : 'text-center' }}">

                                        @if($campo_extra_fornecedor)
                                            {{ $campo_extra_fornecedor }}
                                            @else
                                            <span class="text-warning">
                                                <i class="fa fa-exclamation-circle"></i>
                                                Não informado
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{--Equalizações Técnicas--}}
        @if($quadro->qcTipoEqualizacaoTecnicas()->count())
            <div class="box box-muted" id="box_equalizacoes_tecnicas">
                <div class="box-header with-border">
                    <i class="fa fa-list-ul"></i>
                    Equalizações Técnicas
                    <button type="button" class="btn btn-default btn-xs pull-right"
                            onclick="expandeEncolhe('box_equalizacoes_tecnicas');">
                        <i class="iconeExpandeEncolhe fa fa-minus"></i>
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-condensed table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Item</th>
                            @foreach($qcFornecedores as $qcFornecedor)
                                <th>
                                    {{ $qcFornecedor->fornecedor->nome }}
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>

                            @foreach($equalizacoes as $equalizacao)
                                <tr>
                                    <td class="text-left">
                                        {{ $equalizacao->nome }}
                                        <button type="button"
                                                class="btn btn-default btn-flat btn-xs js-sweetalert"
                                                data-title="{{ $equalizacao->nome }}"
                                                data-text="{{ $equalizacao->descricao }}">
                                            <i class="fa fa-info-circle text-primary"></i> detalhes
                                        </button>

                                        @if($equalizacao->obrigatorio)
                                            <span class="text-warning pull-left" title="Obrigatório">
                                              <i class="fa fa-exclamation-circle"></i>
                                            </span>
                                        @endif
                                    </td>
                                    @foreach($qcFornecedores as $qcFornecedor)
                                        <td>
                                            @php
                                                $check = $qcFornecedor->qcFornecedorEqualizacaoChecks()
                                                        ->where('checkable_id',$equalizacao->id)
                                                        ->where('checkable_type', $equalizacao->table)
                                                        ->first();
                                            @endphp
                                            @if($check)
                                                @if($check->checkable->obrigatorio)
                                                    <span class="text-info">
                                                      <i class="fa fa-check"></i>
                                                    </span>
                                                @else
                                                    @if($check->checked)
                                                        <span class="text-success">
                                                        <i class="fa fa-check"></i> Concorda
                                                      </span>
                                                    @else
                                                        <span class="text-danger">
                                                        <i class="fa fa-times"></i> Não Concorda
                                                      </span>
                                                    @endif

                                                    @if(strlen($check->obs))
                                                        <button type="button"
                                                                title="Considerações"
                                                                class="btn btn-warning btn-flat btn-xs js-sweetalert"
                                                                data-title="{{ $equalizacao->nome }}"
                                                                data-text="{{ $check->obs }}">
                                                            <i class="fa fa-info"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{--Demais condições--}}
        @if(count($qcFornecedores))
            <div class="box box-muted" id="box_demais_condicoes">
                <div class="box-header with-border">
                    <i class="fa fa-list-ul"></i>
                    Demais condições
                    <button type="button" class="btn btn-default btn-xs pull-right"
                            onclick="expandeEncolhe('box_demais_condicoes');">
                        <i class="iconeExpandeEncolhe fa fa-minus"></i>
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-striped table-condensed table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Detalhes</th>
                            @php
                            $porcentagem_material = '';
                            $porcentagem_servico = '';
                            $porcentagem_faturamento_direto = '';
                            $nf_material = '';
                            $nf_servico = '';
                            $nf_locacao = '';
                            @endphp
                            @foreach($qcFornecedores as $qcFornecedor)
                                <th>
                                    {{ $qcFornecedor->fornecedor->nome }}
                                </th>

                                @php
                                    $porcentagem_material .= '<td>'.$qcFornecedor->porcentagem_material.' <span>%</span></td>';
                                    $porcentagem_servico .= '<td>'.$qcFornecedor->porcentagem_servico.' <span>%</span></td>';
                                    $porcentagem_faturamento_direto .= '<td>'.$qcFornecedor->porcentagem_faturamento_direto.' <span>%</span></td>';
                                    $nf_material .= '<td>'.($qcFornecedor->nf_material ? '<span style="color:green">SIM</span>' : '<span style="color:red">NÃO</span>').'</td>';
                                    $nf_servico .= '<td>'.($qcFornecedor->nf_servico   ? '<span style="color:green">SIM</span>' : '<span style="color:red">NÃO</span>').'</td>';
                                    $nf_locacao .= '<td>'.($qcFornecedor->nf_locacao   ? '<span style="color:green">SIM</span>' : '<span style="color:red">NÃO</span>').'</td>';
                                @endphp
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left">% Material</td>
                                {!! $porcentagem_material !!}
                            </tr>
                            <tr>
                                <td class="text-left">% Serviço</td>
                                {!! $porcentagem_servico !!}
                            </tr>
                            <tr>
                                <td class="text-left">% Faturamento Direto</td>
                                {!! $porcentagem_faturamento_direto !!}
                            </tr>
                            <tr>
                                <td class="text-left">NF Material</td>
                                {!! $nf_material !!}
                            </tr>
                            <tr>
                                <td class="text-left">NF Serviço</td>
                                {!! $nf_servico !!}
                            </tr>
                            <tr>
                                <td class="text-left">NF Locação</td>
                                {!! $nf_locacao !!}
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Gráficos --}}
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    {{-- Gráfico Valor Total por Fornecedor --}}
                    <div class="col-md-6">
                        <div class="box box-muted box-chart" id="box_total_por_fornecedor">
                            <div class="box-header with-border">
                                <i class="fa fa-bar-chart-o"></i>
                                Valor Total / Fornecedor
                                <button type="button" class="btn btn-default btn-xs pull-right"
                                        onclick="expandeEncolhe('box_total_por_fornecedor');">
                                    <i class="iconeExpandeEncolhe fa fa-minus"></i>
                                </button>
                            </div>
                            <div class="box-body">
                                <div style="position: relative; height: 480px">
                                    <canvas id="chart-total-fornecedor"
                                            data-labels="{{
                                                $qcFornecedores
                                                  ->pluck('fornecedor')
                                                  ->flatten()
                                                  ->pluck('nome')
                                                  ->implode('||')
                                                }}"
                                            data-values="{{
                                              $qcFornecedores
                                                ->map(function($qcFornecedor) {
                                                  return $qcFornecedor->itens->sum('valor_total') + $qcFornecedor->getOriginal('valor_frete');
                                                })
                                                ->implode('||')
                                              }}">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Gráfico Insumo Por Fornecedor--}}
                    <div class="col-md-6">
                        <div class="box box-muted box-chart" id="box_insumo_por_fornecedor">
                            <div class="box-header with-border">
                                <i class="fa fa-bar-chart-o"></i>
                                Insumo / Fornecedor
                                <button type="button" class="btn btn-default btn-xs pull-right"
                                        onclick="expandeEncolhe('box_insumo_por_fornecedor');">
                                    <i class="iconeExpandeEncolhe fa fa-minus"></i>
                                </button>
                            </div>

                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!!
                                      Form::select(
                                        'insumo',
                                        $quadro->itens->pluck('insumo')->flatten()->pluck('nome', 'id')->toArray()+ [4131=>'SER FRETE'],
                                        null,
                                        ['class' => 'select2 form-control', 'id' => 'insumo']
                                      )
                                    !!}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        Menor preço
                                        <canvas id="UgCanvas" width="40" height="12" style="border:1px solid blue; background-color: blue;"></canvas>
                                    </div>

                                    <div class="col-md-6">
                                        Valor do OI
                                        <canvas id="UgCanvas" width="40" height="12" style="border:1px solid red; background-color: red;"></canvas>
                                    </div>
                                </div>
                                <div style="position: relative; max-height: 420px">
                                    <canvas id="chart-insumo-fornecedor"
                                            data-data='{{ json_encode($ofertas) }}'>
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="equalizacao-tecnica" class="modal fade" role="dialog">
        <div clasqus="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Equalizações Técnicas</h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <div id="modal-finalizar" class="modal fade" role="dialog">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Quadro de Concorrência</h4>
                </div>
                <div class="modal-body">
                    {!!
                      Form::open([
                        'route' => ['quadroDeConcorrencia.avaliar', $quadro->id],
                        'id' => 'form-finalizar'
                      ])
                    !!}
                    <div class="box box-warning collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <button type="button" class="btn btn-warning btn-flat btn-flat btn-lg"
                                        data-widget="collapse">
                                    Nova Rodada
                                </button>
                            </h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body" id="fornecedores-container">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('qcFornecedores', 'Fornecedores:') !!}
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('fornecedores', ['' => 'Escolha...'],
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'id'=>'fornecedor'
                                    ]) !!}
                                </div>
                                <div class="col-md-12">
                                    <button type="button" title="Cadastrar Fornecedor Temporariamente"
                                            style="margin-top: 5px; margin-bottom: 5px;"
                                            id="cadastrarFornecedorTemporariamente"
                                            onclick="cadastraFornecedor()"
                                            class="btn btn-block btn-sm btn-flat btn-info">
                                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                                        Cadastrar Temporariamente
                                    </button>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('fornecedores_temp', ['' => 'Fornecedores Temporários...'],
                                    null,
                                    [
                                        'class' => 'form-control',
                                        'id'=>'fornecedor_temp'
                                    ]) !!}
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <p>Selecione os fornecedores que permanecerão na próxima rodada:</p>
                                @foreach($qcFornecedores as $qcFornecedor)
                                    <div>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="fornecedores[]"
                                                   value="{{ $qcFornecedor->fornecedor_id }}">
                                            {{ $qcFornecedor->fornecedor->nome }}
                                        </label>
                                    </div>
                                    <?php
                                    $qcFornecedorCount = $qcFornecedor->id;
                                    ?>
                                @endforeach
                                <div id="fornecedoresSelecionados"></div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-warning pull-right btn-lg btn-flat" id="nova-rodada">
                                <i class="fa fa-refresh" aria-hidden="true"></i> Gerar nova rodada
                            </button>
                        </div>
                    </div>
                    <div class="box box-success collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <button type="button" class="btn btn-success btn-lg btn-flat" data-widget="collapse">
                                    Finalizar Quadro de Concorrência
                                </button>
                            </h3>
                            <div class="box-tools pull-right">

                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-align-middle">
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>Un. de medida</th>
                                    <th>Qtd</th>
                                    @foreach($qcFornecedores as $qcFornecedor)
                                        <th>
                                            {{ Form::radio('qcFornecedor_escolhido', 1, false,
                                            [
                                                'class' => 'icheck_destroy',
                                                'style' => 'width: 18px;height: 18px;',
                                                'onclick' => 'marcarDesmarcarTudo('.$qcFornecedor->id.');',
                                                'id' => 'marcarDesmarcarTudoCheck'.$qcFornecedor->id
                                            ]) }}
                                            <label for="marcarDesmarcarTudoCheck{{ $qcFornecedor->id }}">
                                                {{ $qcFornecedor->fornecedor->nome }}
                                            </label>
                                            <table style="width:100%;margin-top: 20px;">
                                                <tr>
                                                    <th>Valor unitário</th>
                                                    <th>Valor total</th>
                                                </tr>
                                            </table>
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($quadro->itens as $item)
                                    <tr class="js-insumo-row">
                                        <td class="text-left">
                                            {{ $item->insumo->codigo }}
                                        </td>
                                        <td class="text-left">
                                            {{ $item->insumo->nome }}
                                        </td>
                                        <td class="text-left">
                                            {{ $item->insumo->unidade_sigla }}
                                        </td>
                                        <td>
                                            {{ float_to_money($item->qtd, '') }}
                                        </td>
                                        @foreach($qcFornecedores as $qcFornecedor)
                                            @php
                                                $qcItemQcFornecedor = $qcFornecedor->itens
                                                  ->where('qc_item_id', $item->id)
                                                  ->first();
                                            @endphp
                                            <th class="text-center">
                                                <table style="width:100%">
                                                    <tr>
                                                        <td>
                                                            <label style="font-weight: normal;">
                                                                @if(isset($qcItemQcFornecedor->valor_unitario))
                                                                    {{ float_to_money($qcItemQcFornecedor->valor_unitario) }}
                                                                @endif
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <div class="radio">
                                                                <label>
                                                                    @if(isset($qcItemQcFornecedor->valor_total))
                                                                        {!!
                                                                          Form::radio(
                                                                            "vencedores[{$item->id}]",
                                                                            $qcItemQcFornecedor->id,
                                                                            false,
                                                                            [
                                                                                'class' => 'icheck_destroy qcFornecedorInsumo_'.
                                                                                            $qcFornecedor->id,
                                                                                'style' => 'width: 18px;height: 18px;'
                                                                            ]
                                                                          )
                                                                        !!}
                                                                        {{ float_to_money($qcItemQcFornecedor->valor_total) }}
                                                                    @else
                                                                        Sem proposta
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </th>
                                        @endforeach
                                    </tr>
                                @endforeach
                                @if($quadro->hasMaterial())
                                    <tr>
                                        <td colspan="4" class="text-right"><strong>Frete</strong></td>
                                        @foreach($qcFornecedores as $qcFornecedor)
                                            <td class="text-center">
                                                {{ $qcFornecedor->tipo_frete }}
                                                <div class="input-group">
                                                    <span class="input-group-addon">R$</span>
                                                    <input type="text"
                                                           class="form-control money"
                                                           value="{{ $qcFornecedor->valor_frete }}"
                                                           name="valor_frete[{{$qcFornecedor->id}}]">
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success pull-right btn-lg btn-flat" id="finalizar">
                                <i class="fa fa-trophy" aria-hidden="true"></i>
                                Finalizar Quadro de Concorrência
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        window.urlEqualizacao = "/quadro-de-concorrencia/{{ $quadro->id }}/equalizacao-tecnica/";

        var qtdFornecedores = parseInt({!! (isset($qcFornecedorCount) ? $qcFornecedorCount : 0) !!});
        $(function () {
            $('#fornecedor').select2({
                allowClear: true,
                placeholder: "-",
                language: "pt-BR",
                ajax: {
                    url: "/catalogo-acordos/buscar/busca_fornecedores",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },

                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResult, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelection // omitted for brevity, see the source of this page

            });

            $('#fornecedor_temp').select2({
                allowClear: true,
                placeholder: "Fornecedores Temporários",
                language: "pt-BR",
                ajax: {
                    url: "/fornecedores/busca-temporarios",
                    dataType: 'json',
                    delay: 250,

                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },

                    processResults: function (result, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;

                        return {
                            results: result.data,
                            pagination: {
                                more: (params.page * result.per_page) < result.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 1,
                templateResult: formatResultNomeId, // omitted for brevity, see the source of this page
                templateSelection: formatResultSelectionNomeId // omitted for brevity, see the source of this page

            });

            $('#fornecedor').on('select2:select', function (e) {
                addFornecedor()
            });

            $('#fornecedor_temp').on('select2:select', function (e) {
                addFornecedorTemp();
            });

            $('.icheck_destroy').iCheck('destroy');

            $("#fixTable").tableHeadFixer({'left' : 3, 'head' : true});

        });

        function expandeEncolhe(qual) {
            if($('#'+qual+' .box-body').is(':visible')){
                $('#'+qual+' .box-body').hide();
                $('#'+qual+' .iconeExpandeEncolhe').removeClass('fa-minus');
                $('#'+qual+' .iconeExpandeEncolhe').addClass('fa-plus');
            }else{
                $('#'+qual+' .box-body').show();
                $('#'+qual+' .iconeExpandeEncolhe').removeClass('fa-plus');
                $('#'+qual+' .iconeExpandeEncolhe').addClass('fa-minus');
            }

        }

        // Fornecedor
        function cadastraFornecedor() {
            funcaoPosCreate = "preencheFornecedor();";
            $.colorbox({
                href: "/fornecedores/create?modal=1",
                iframe: true,
                width: '90%',
                height: '90%'
            });
        }

        function preencheFornecedor() {
            qtdFornecedores++;
            var nomeFornecedor = novoObjeto.nome;
            var qcFornecedorHTML = '<div><label class="checkbox-inline" id="qcFornecedor_id' + qtdFornecedores + '">' +
                    '<input type="checkbox" name="qcFornecedores[][fornecedor_id]" value="' + novoObjeto.id + '">' +
                    nomeFornecedor +
                    '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                    ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                    '<i class="fa fa-trash" aria-hidden="true"></i>' +
                    '</button>' +
                    '</label></div>';

            $('#fornecedor').val(null).trigger("change");
            $('#fornecedoresSelecionados').append(qcFornecedorHTML);

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        }

        function addFornecedor() {
            qtdFornecedores++;
            if ($('#fornecedor').val()) {
                var nomeFornecedor = $('#fornecedor').select2('data');
                var qcFornecedorHTML = '<div><label class="checkbox-inline" id="qcFornecedor_id' + qtdFornecedores + '">' +
                        '<input type="checkbox" name="qcFornecedoresMega[]" value="' + $('#fornecedor').val() + '"> ' +
                        nomeFornecedor[0].agn_st_nome +
                        '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                        ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '</label></div>';

                $('#fornecedor').val(null).trigger("change");
                $('#fornecedoresSelecionados').append(qcFornecedorHTML);
                $('#fornecedor').select2('open');
            }

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        }

        function addFornecedorTemp() {
            qtdFornecedores++;
            if ($('#fornecedor_temp').val()) {
                var nomeFornecedor = $('#fornecedor_temp').select2('data');
                var qcFornecedorHTML = '<div><label class="checkbox-inline" id="qcFornecedor_id' + qtdFornecedores + '">' +
                        '<input type="checkbox" name="qcFornecedores[][fornecedor_id]" value="' + $('#fornecedor_temp').val() + '"> ' +
                        nomeFornecedor[0].nome +
                        '<button type="button" title="Remover" class="btn btn-flat btn-danger btn-xs pull-right" ' +
                        ' onclick="removerFornecedor(' + qtdFornecedores + ',0)">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '</label></div>';

                $('#fornecedor_temp').val(null).trigger("change");
                $('#fornecedoresSelecionados').append(qcFornecedorHTML);
                $('#fornecedor_temp').select2('open');
            }

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        }

        function removerFornecedor(qual, qcFornecedorId) {
            if (qcFornecedorId) {
                // Remover no banco
                swal({
                    title: 'Deseja remover este fornecedor?',
                    text: 'Após a remoção não será possível mais recuperar o registro.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Sim, tenho certeza!",
                    cancelButtonText: "Não",
                    closeOnConfirm: false
                }, function () {
                    $.ajax("/quadro-de-concorrencia/" + quadroDeConcorrenciaId + "/remover-fornecedor/" + qual)
                            .done(function (retorno) {
                                $('#qcFornecedor_id' + qual).remove();
                                swal('Removido', '', 'success');
                            }).fail(function (jqXHR, textStatus, errorThrown) {
                        swal('Erro', jqXHR.responseText, 'error');
                    });
                });
            } else {
                // Apenas remove o HTML
                $('#qcFornecedor_id' + qual).remove();
            }
        }

        function formatResult(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.agn_st_nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelection(obj) {
            if (obj.agn_st_nome) {
                return obj.agn_st_nome;
            }
            return obj.text;
        }

        function formatResultNomeId(obj) {
            if (obj.loading) return obj.text;

            var markup = "<div class='select2-result-obj clearfix'>" +
                    "   <div class='select2-result-obj__meta'>" +
                    "       <div class='select2-result-obj__title'>" + obj.nome + "</div>" +
                    "   </div>" +
                    "</div>";

            return markup;
        }

        function formatResultSelectionNomeId(obj) {
            if (obj.nome) {
                return obj.nome;
            }
            return obj.text;
        }

        function marcarDesmarcarTudo(id) {
            var marcarDesmarcarTudoCheck = $('#marcarDesmarcarTudoCheck' + id).prop('checked');
            if (marcarDesmarcarTudoCheck) {
                $('.qcFornecedorInsumo_' + id).prop('checked', true);
            } else {
                $('.qcFornecedorInsumo_' + id).prop('checked', false);
            }
        }
    </script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    {!! $dataTable->scripts() !!}
@endsection

