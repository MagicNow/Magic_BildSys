@extends('layouts.front')
@section('styles')
    <style type="text/css">

        #totalInsumos h5{
            font-weight: bold;
            color: #4a4a4a;
            font-size: 13px;
            margin: 0 10px;
            opacity: 0.5;
            text-transform: uppercase;
        }
        #totalInsumos h4{
            font-weight: bold;
            margin: 0 10px;
            color: #4a4a4a;
            font-size: 22px;
        }
        #totalInsumos{
            margin-bottom: 20px;
        }
    </style>
@stop
@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <div class="col-md-6">
                    <span class="pull-left title">
                        <h3>
                            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                             <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <span>Detalhar Ordem de Compra</span>
                        </h3>
                    </span>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{!! route('retroalimentacaoObras.create') !!}" class="btn btn-default btn-lg btn-flat">
                        Retroalimentação
                    </a>
                    @if(!is_null($ordemDeCompra->aprovado))
                        @if($ordemDeCompra->aprovado)
                            <span class="btn-lg btn-flat text-success" title="Aprovado">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </span>
                        @else
                            <span class="text-danger btn-lg btn-flat" title="Reprovado">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </span>
                        @endif
                    @else
                        @if($aprovavelTudo['podeAprovar'])
                            @if($aprovavelTudo['iraAprovar'])
                                <div class="btn-group" role="group" id="blocoOCAprovacao{{ $ordemDeCompra->id }}" aria-label="...">
                                    <button type="button" title="Aprovar Todos os itens"
                                            onclick="workflowAprovaReprova({{ $ordemDeCompra->id }},'OrdemDeCompraItem',1,'blocoOCAprovacao{{ $ordemDeCompra->id }}','OC {{ $ordemDeCompra->id }}', {{ $ordemDeCompra->id }}, 'OrdemDeCompra', 'itens');"
                                            class="btn btn-default btn-lg btn-flat">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" title="Reprovar Todos os itens"
                                            onclick="workflowAprovaReprova({{ $ordemDeCompra->id }},'OrdemDeCompraItem',0, 'blocoOCAprovacao{{ $ordemDeCompra->id }}','OC {{ $ordemDeCompra->id }}', {{ $ordemDeCompra->id }}, 'OrdemDeCompra', 'itens');"
                                            class="btn btn-default btn-lg btn-flat">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @else
                                @if($aprovavelTudo['jaAprovou'])
                                    @if($aprovavelTudo['aprovacao'])
                                        <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </span>
                                    @else
                                        <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </span>
                                    @endif
                                @else
                                    {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
                                    <button type="button" title="{{ $aprovavelTudo['msg'] }}"
                                            onclick="swal('{{ $aprovavelTudo['msg'] }}','','info');"
                                            class="btn btn-default btn-lg btn-flat">
                                        <i class="fa fa-info" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
    <div class="content">
        <h6>Dados Informativos</h6>
        <div class="row">
            <div class="col-md-2 form-group">
                {!! Form::label('id', 'Código da O.C.') !!}
                <p class="form-control input-lg highlight text-center">{!! $ordemDeCompra->id !!}</p>
            </div>

            <div class="col-md-4 form-group">
                {!! Form::label('obra', 'Obra') !!}
                <p class="form-control input-lg">{!! $ordemDeCompra->obra->nome !!}</p>
            </div>
            <div class="col-md-2 form-group">
                {!! Form::label('created_at', 'Data de Criação') !!}
                <p class="form-control input-lg">{!! $ordemDeCompra->created_at->format('d/m/Y') !!}</p>
            </div>
            <div class="col-md-4 form-group">
                {!! Form::label('user_id', 'Responsável') !!}
                <p class="form-control input-lg">{!! $ordemDeCompra->user->name !!}</p>
            </div>

        </div>
        <hr>
        <div class="row" id="totalInsumos">
            <div class="col-md-4">
                <h6>Total de Insumos</h6>
            </div>
            <div class="col-md-2 text-right borda-direita">
                <h5>ORÇAMENTO INICIAL</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($orcamentoInicial,2,',','.') }}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Nos itens desta O.C.">
                <h5>TOTAL À GASTAR</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($totalAGastar,2,',','.') }}
                </h4>
            </div>
            <div class="col-md-2 text-right borda-direita" title="Até o momento em todos os itens desta O.C.">
                <h5>QUANTIDADE REALIZADA</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($realizado,2,',','.') }}
                </h4>
            </div>
            <div class="col-md-2 text-right" title="Restante do Orçamento Inicial em relação aos itens desta O.C.">
                <h5>SALDO</h5>
                <h4>
                    <small class="pull-left">R$</small>
                    {{ number_format($saldo,2,',','.') }}
                </h4>
            </div>

        </div>

        @foreach($itens as $item)
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-md-10">
                    <h4 class="highlight">{{ $item->insumo->codigo . ' - '. $item->insumo->nome }}</h4>
                </div>
                <div class="col-md-2 text-right">
                    @if(!is_null($item->aprovado))
                        @if($item->aprovado)
                            <button type="button" disabled="disabled"
                                    class="btn btn-success btn-lg btn-flat">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                        @else
                            <button type="button" disabled="disabled"
                                    class="btn btn-danger btn-lg btn-flat">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        @endif
                    @else
                        <?php
                            $workflowAprovacao = \App\Repositories\WorkflowAprovacaoRepository::verificaAprovacoes('OrdemDeCompraItem', $item->id, Auth::user());
                        ?>
                        @if($workflowAprovacao['podeAprovar'])
                            @if($workflowAprovacao['iraAprovar'])
                                <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $item->id }}" aria-label="...">
                                    <button type="button" onclick="workflowAprovaReprova({{ $item->id }},'OrdemDeCompraItem',1,'blocoItemAprovaReprova{{ $item->id }}','Insumo {{ $item->insumo->codigo }}',0, '', '');"
                                            class="btn btn-default btn-lg btn-flat"
                                            title="Aprovar Este item">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" onclick="workflowAprovaReprova({{ $item->id }},'OrdemDeCompraItem',0, 'blocoItemAprovaReprova{{ $item->id }}','Insumo {{ $item->insumo->codigo }}',0, '', '');"
                                            class="btn btn-default btn-lg btn-flat"
                                            title="Reprovar Este item">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </div>
                            @else
                                @if($workflowAprovacao['jaAprovou'])
                                    @if($workflowAprovacao['aprovacao'])
                                            <span class="btn-lg btn-flat text-success" title="Aprovado por você">
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                            </span>
                                    @else
                                            <span class="text-danger btn-lg btn-flat" title="Reprovado por você">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </span>
                                    @endif
                                @else
                                    {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
                                        <button type="button" title="{{ $workflowAprovacao['msg'] }}"
                                                onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
                                                class="btn btn-default btn-lg btn-flat">
                                            <i class="fa fa-info" aria-hidden="true"></i>
                                        </button>
                                @endif
                            @endif
                        @endif
                    @endif
            </div>
            <div class="col-md-12 table-responsive margem-topo">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Unidade Medida</th>
                            <th class="text-center">Qtd. O. Inicial</th>
                            <th class="text-center">Valor O. Inicial</th>
                            <th class="text-center">Qtd. Realizada</th>
                            <th class="text-center">Valor Realizado</th>
                            <th class="text-center">Qtd. Restante</th>
                            <th class="text-center">Valor Restante</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{ $item->unidade_sigla . ' - '.$item->unidade->descricao }}</td>
                            <td class="text-center">{{ number_format($item->qtd_inicial, 2, ',','.') }}</td>
                            <td class="text-center"><small class="pull-left">R$</small> {{ number_format($item->preco_inicial, 2, ',','.') }}</td>
                            <td class="text-center">{{ number_format(doubleval($item->qtd_realizada), 2, ',','.') }}</td>
                            <td class="text-center"><small class="pull-left">R$</small> {{ number_format( doubleval($item->valor_realizado), 2, ',','.') }}</td>
                            <td class="text-center">{{ number_format( $item->qtd_inicial-doubleval($item->qtd_realizada), 2, ',','.') }}</td>
                            <td class="text-center"><small class="pull-left">R$</small> {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12 table-responsive margem-topo">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Qtd. Saldo</th>
                            <th class="text-center">Valor Saldo</th>
                            <th class="text-center">Qtd. Solicitada</th>
                            <th class="text-center">Valor Solicitado</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Data de Uso</th>
                            <th class="text-center">Emergencial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{ number_format( $item->qtd_inicial - doubleval($item->qtd_realizada), 2, ',','.') }}</td>
                            <td class="text-center"><small class="pull-left">R$</small> {{ number_format( $item->preco_inicial-doubleval($item->valor_realizado), 2, ',','.') }}</td>
                            <td class="text-center"><strong>{{ $item->qtd }}</strong></td>
                            <td class="text-center"><small class="pull-left">R$</small> <strong>{{ number_format(doubleval($item->valor_total), 2, ',','.') }}</strong></td>
                            <td class="text-center"><i class="fa fa-circle {{ (($item->qtd_realizada) > $item->qtd_inicial) ? 'text-danger': 'text-success'  }}" aria-hidden="true"></i> </td>
                            <td class="text-center">{{ $item->sugestao_data_uso ? $item->sugestao_data_uso->format('d/m/Y') : ''  }}</td>
                            <td class="text-center">{!! $item->emergencial?'<strong class="text-danger"> <i class="fa fa-exclamation-circle" aria-hidden="true"></i> SIM</strong>':'NÃO' !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6 margem-topo borda-direita">
                <div class="row">
                    <div class="col-md-4 label-bloco">
                        Justificativa de compra:
                    </div>
                    <div class="bloco-texto-conteudo col-md-7">
                        {{ $item->justificativa }}
                    </div>
                </div>
            </div>
            <div class="col-md-6 margem-topo">
                <div class="col-md-4 label-bloco">
                    Observações ao fornecedor:
                </div>
                <div class="bloco-texto-conteudo col-md-7">
                    {{ $item->obs }}
                </div>
            </div>
            <div class="col-md-6 margem-topo borda-direita">
                <div class="row">
                    <div class="col-md-4 label-bloco">
                        Tabela TEMS:
                    </div>
                    <div class="bloco-texto-conteudo col-md-7">
                        {{ $item->tems }}
                    </div>

                    <div class="col-md-4 label-bloco margem-topo">
                        Sugestão de Contrato:
                    </div>
                    <div class="bloco-texto-conteudo col-md-7 margem-topo">
                        {{ $item->sugestao_contrato_id }}
                    </div>
                </div>
            </div>
            <div class="col-md-6 margem-topo">
                @if($item->anexos)
                <div class="col-md-4 label-bloco">
                    Arquivos anexos:
                </div>
                <div class="col-md-8">
                    <div class="row">
                        @foreach($item->anexos as $anexo)
                            <div class="bloco-texto-linha col-md-9">{{ substr($anexo->arquivo, strrpos($anexo->arquivo,'/')+1  )  }}</div>
                            <div class="col-md-2">
                                <a href="{{ Storage::url($anexo->arquivo) }}" class="btn btn-default btn-block" target="_blank" >
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </div>

                        @endforeach
                    </div>

                </div>
                @endif

            </div>

        </div>
    </div>
    @endforeach
    <div class="pg text-center">
            {{ $itens->links() }}
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    <?php
            $options_motivos = "<option value=''>Escolha...</option>";
            foreach($motivos_reprovacao as $motivo_id=>$motivo_nome){
                $options_motivos .= "<option value='".$motivo_id."'>".$motivo_nome."</option>";
            }
    ?>
    options_motivos = "{!! $options_motivos !!}";


</script>
@stop