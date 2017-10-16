@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Medicão - {{ $medicao->id }}

            <span class="pull-right">

            @if(!is_null($medicao->aprovado))
                @if($medicao->aprovado)
                    <button type="button" disabled="disabled"
                            class="btn btn-success btn-sm btn-flat">
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                @else
                    <button type="button" disabled="disabled"
                            class="btn btn-danger btn-sm btn-flat">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                @endif
            @else
                <?php
                $workflowAprovacao = \App\Repositories\WorkflowAprovacaoRepository::verificaAprovacoes('Medicao', $medicao->id, Auth::user());
                ?>
                @if($workflowAprovacao['podeAprovar'])
                    @if($workflowAprovacao['iraAprovar'])
                        <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $medicao->id }}" aria-label="...">
                            <button type="button" onclick="workflowAprovaReprova({{ $medicao->id }},'Medicao',1,'blocoItemAprovaReprova{{ $medicao->id }}','Medição {{ $medicao->id }}',0, '', '', true);"
                                    class="btn btn-success btn-sm btn-flat"
                                    title="Aprovar este item">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button type="button" onclick="workflowAprovaReprova({{ $medicao->id }},'Medicao',0, 'blocoItemAprovaReprova{{ $medicao->id }}','Medição {{ $medicao->id }}',0, '', '', true);"
                                    class="btn btn-danger btn-sm btn-flat"
                                    title="Reprovar este item">
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
                                <span class="text-danger btn-sm btn-flat" title="Reprovado por você">
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                </span>
                            @endif
                        @else
                            {{--Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento--}}
                            <button type="button" title="{{ $workflowAprovacao['msg'] }}"
                                    onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
                                    class="btn btn-default btn-sm btn-flat">
                                <i class="fa fa-info" aria-hidden="true"></i>
                            </button>
                        @endif
                    @endif
                @endif
            @endif
        </span>

        </h1>
    </section>
    <?php
    $contratoItemApropriacao = $medicao->mcMedicaoPrevisao->contratoItemApropriacao;
    $mcMedicaoPrevisao = $medicao->mcMedicaoPrevisao;
    $medicaoServico = $medicao->medicaoServicos;
    ?>
    <div class="content">
        <?php
        if($medicao->aprovacoes()){
            $motivos_reprovacao = $medicao->aprovacoes()
                    ->where('aprovado', 0)
                    ->where('created_at', '>=', $medicao->updated_at)
                    ->orderBy('id', 'DESC')
                    ->get();
        }else{
            $motivos_reprovacao = [];
        }
        ?>
        @if(count($motivos_reprovacao))
            <div class="alert alert-danger" role="alert" id="alert_{{ $medicao->id }}">
                <h4><i class="fa fa-exclamation"></i> Reprovações</h4>
                @foreach($motivos_reprovacao as $motivo_reprovacao)
                    @if (!$loop->first)
                        <hr>
                    @endif
                    @if($motivo_reprovacao->user)
                        Usuário: <strong>{{$motivo_reprovacao->user->name}}</strong><br>
                    @endif
                    @if($motivo_reprovacao->workflowReprovacaoMotivo)
                        Motivo de reprovação: <span style="font-weight:bold;">{{$motivo_reprovacao->workflowReprovacaoMotivo->nome}}</span><br>
                    @endif
                    Justificativa: <span style="font-weight:bold;">{{$motivo_reprovacao->justificativa}}</span>

                @endforeach
            </div>
        @endif
        <div class="box box-default">
            <div class="box-body">
                <h4>Obra: <span class="label bg-orange">{{ $contratoItemApropriacao->contratoItem->contrato->obra->nome }}</span></h4>
                <h4>
                    Contrato: <span class="label bg-navy">{{ $contratoItemApropriacao->contratoItem->contrato->id }}</span>
                    <span class="label bg-navy">{{ $contratoItemApropriacao->contratoItem->contrato->fornecedor->nome }}</span>
                </h4>
                <h4>Insumo: <span class="label label-warning">{{ $contratoItemApropriacao->codigo_insumo }}</span> <span class="label label-warning">{{ $contratoItemApropriacao->insumo->nome }}</span></h4>
                @if($medicaoServico)
                    <h4>Período do Serviço: <span class="label label-default"> {{ with(new\Carbon\Carbon($medicaoServico->periodo_inicio))->format('d/m/Y') }}</span> à <span class="label label-default">{{ with(new\Carbon\Carbon($medicaoServico->periodo_termino))->format('d/m/Y') }}</span></h4>
                @endif
                @if($mcMedicaoPrevisao)
                    <h4>{{ $mcMedicaoPrevisao->memoriaCalculoBloco->estruturaObj->nome }} -
                        {{ $mcMedicaoPrevisao->memoriaCalculoBloco->pavimentoObj->nome }} -
                        {{ $mcMedicaoPrevisao->memoriaCalculoBloco->trechoObj->nome }}
                        <span class="label label-default">
                            {{ float_to_money($mcMedicaoPrevisao->qtd,'') .' '. $mcMedicaoPrevisao->unidade_sigla }}
                        </span>
                        @if($medicoes)
                            @if($medicoes->count())
                                <span class="label label-warning">
                                {{ number_format( ( ($medicoes->first()->qtd/$mcMedicaoPrevisao->qtd) * 100),2,',','.')  }}% já medido
                            </span>
                            @endif
                        @endif
                    </h4>
                @endif
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('medicoes.show_fields')
                    <button type="button" onclick="history.go(-1);" class="btn btn-default btn-lg btn-flat"><i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        @parent
        <?php
                $options_motivos = "<option value=''>Escolha...</option>";
                foreach($motivos_de_reprovacao as $motivo_id=>$motivo_nome){
                    $options_motivos .= "<option value='".$motivo_id."'>".$motivo_nome."</option>";
                }
                ?>
                options_motivos = "{!! $options_motivos !!}";

    </script>
@stop
