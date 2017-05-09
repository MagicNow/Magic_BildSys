@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Quadro De Concorrencia {{ $quadroDeConcorrencia->id }}
            <small class="label label-default pull-right margin10">
                <i class="fa fa-clock-o"
                   aria-hidden="true"></i> {{ $quadroDeConcorrencia->created_at->format('d/m/Y H:i') }}
                <i class="fa fa-user" aria-hidden="true"></i> {{ $quadroDeConcorrencia->user->name }}
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
                            <button type="button" onclick="workflowAprovaReprova({{ $quadroDeConcorrencia->id }},
                                    'QuadroDeConcorrencia',1,'blocoItemAprovaReprova{{ $quadroDeConcorrencia->id }}',
                                    'Q.C. {{ $quadroDeConcorrencia->id }}',0, '', '');"
                                    class="btn btn-default btn-lg btn-flat"
                                    title="Aprovar Este item">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <button type="button" onclick="workflowAprovaReprova({{ $quadroDeConcorrencia->id }},
                                    'QuadroDeConcorrencia',0, 'blocoItemAprovaReprova{{ $quadroDeConcorrencia->id }}',
                                    'Q.C. {{ $quadroDeConcorrencia->id }}',0, '', '');"
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
            @elseif($quadroDeConcorrencia->qc_status_id==5)
                <button type="button" class="btn btn-lg btn-success btn-flat" style="margin-left: 20px" onclick="abrirConcorrencia();">
                    <i class="fa fa-play-circle-o " aria-hidden="true"></i> Abrir concorrência
                </button>
            @endif
        </h1>
    </section>
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
