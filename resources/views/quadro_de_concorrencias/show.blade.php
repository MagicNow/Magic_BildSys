@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Quadro de concorrência {{ $quadroDeConcorrencia->id }}
            <small class="label label-default pull-right margin10">
                <i class="fa fa-clock-o"
                   aria-hidden="true"></i> {{ $quadroDeConcorrencia->created_at->format('d/m/Y H:i') }}
                <i class="fa fa-user" aria-hidden="true"></i> {{ $quadroDeConcorrencia->user ? $quadroDeConcorrencia->user->name : 'Catálogo' }}
            </small>

            <small class="label label-info pull-right margin10" id="qc_status">
                <i class="fa fa-circle" aria-hidden="true" style="color:{{ $quadroDeConcorrencia->status->cor }}"></i>
                {{ $quadroDeConcorrencia->status->nome }}
            </small>

            @if($quadroDeConcorrencia->qc_status_id==3)
                <?php
                $workflowAprovacao = \App\Repositories\WorkflowAprovacaoRepository::verificaAprovacoes(
                        'QuadroDeConcorrencia',
                        $quadroDeConcorrencia->id,
                        Auth::user()
                );
                ?>
                @if($workflowAprovacao['podeAprovar'])
                    @if($workflowAprovacao['iraAprovar'])
                        <span class="text-warning"> ||  Aprovação de Escopo </span>
                        <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $quadroDeConcorrencia->id }}"
                             aria-label="...">
                            <button type="button"
                                    class="btn btn-ms btn-flat btn-primary"
                                    data-toggle="modal"
                                    data-target="#modal-obs">
                                Observação do Aprovador
                            </button>
                            <button type="button" onclick="workflowAprovaReprova({{ $quadroDeConcorrencia->id }},
                                    'QuadroDeConcorrencia',1,'blocoItemAprovaReprova{{ $quadroDeConcorrencia->id }}',
                                    'Q.C. {{ $quadroDeConcorrencia->id }}',0, '', '', true);"
                                    class="btn btn-success btn-md btn-flat"
                                    title="Aprovar">
                                Aprovar Q.C.
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button type="button" onclick="workflowAprovaReprova({{ $quadroDeConcorrencia->id }},
                                    'QuadroDeConcorrencia',0, 'blocoItemAprovaReprova{{ $quadroDeConcorrencia->id }}',
                                    'Q.C. {{ $quadroDeConcorrencia->id }}',0, '', '', true);"
                                    class="btn btn-danger btn-md btn-flat"
                                    title="Reprovar">
                                Reprovar Q.C.
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="modal fade" id="modal-obs" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title">Observações do Aprovador</h4>
                                    </div>
                                    <div class="modal-body">
                                        {{--<textarea class="form-control" id="obs-aprovador" data-set="obs_aprova_qc" rows="15"></textarea>--}}
                                        <textarea class="form-control" id="obs-aprovador" data-key="qc_obs_{{ auth()->id() }}_{{ $quadroDeConcorrencia->id }}" rows="15"></textarea>
                                    </div>
                                </div>
                            </div>
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
            @elseif($quadroDeConcorrencia->qc_status_id==5)
                @shield('quadroDeConcorrencias.edit')
                <button type="button" class="btn btn-lg btn-success btn-flat" style="margin-left: 20px" onclick="abrirConcorrencia({{$quadroDeConcorrencia->id}});">
                    <i class="fa fa-play-circle-o " aria-hidden="true"></i> Abrir concorrência
                </button>
                @endshield
            @elseif($quadroDeConcorrencia->qc_status_id==7)
                @shield('quadroDeConcorrencias.informar_valor')
                    <a href="{{ route('quadroDeConcorrencia.informar-valor', $quadroDeConcorrencia->id) }}" class="btn btn-lg btn-flat btn-info" title="Informar valores">
                        <i class="glyphicon glyphicon-usd"></i> Lançar valores
                    </a>
                @endshield
                @shield('quadroDeConcorrencias.informar_valor')
                    @if($quadroDeConcorrencia->temOfertas())
                        <a href="{{ route('quadroDeConcorrencia.avaliar', $quadroDeConcorrencia->id) }}" class="btn btn-lg btn-primary btn-flat" title="Avaliar Quadro de Concorrência">
                            <i class="glyphicon glyphicon-ok"></i> Avaliar
                        </a>
                    @endif
                @endshield
            @endif
        </h1>
    </section>
    @if($oc_status != 'Em Aberto')
        @include('quadro_de_concorrencias.timeline')
    @endif
    <div class="content">
        <div class="box box-warning">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('quadro_de_concorrencias.show_fields')
                    <a href="{!! route('quadroDeConcorrencias.index') !!}" class="btn btn-default">
                       <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
                    </a>
                </div>
            </div>
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
