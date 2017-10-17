@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Medicão de serviço {{ $medicaoServico->id . ' - Obra '. $medicaoServico->contratoItemApropriacao->contratoItem->contrato->obra->nome }}

            @if(!$medicaoServico->finalizado)
            <a class="btn btn-success btn-flat btn-lg pull-right" href="{{ url('/medicoes/create?contrato_item_apropriacao_id='.$medicaoServico->contrato_item_apropriacao_id.'&medicao_servico_id='.$medicaoServico->id) }}">
                Continuar medição <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
            @endif

            @if($medicaoServico->finalizado)
            <span class="pull-right">
                @if(!is_null($medicaoServico->aprovado))
                    @if($medicaoServico->aprovado)
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
                            <div class="btn-group" role="group" id="blocoMSAprovacao{{ $medicaoServico->id }}" aria-label="...">
                                <button type="button" title="Aprovar todas as medições"
                                        onclick="workflowAprovaReprova({{ $medicaoServico->id }},'Medicao',1,'blocoMSAprovacao{{ $medicaoServico->id }}','Medição de Serviço {{ $medicaoServico->id }}', {{ $medicaoServico->id }}, 'MedicaoServico', 'medicoes');"
                                        class="btn btn-success btn-lg btn-flat">
                                    Aprovar
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <button type="button" title="Reprovar todas as medições"
                                        onclick="workflowAprovaReprova({{ $medicaoServico->id }},'Medicao',0, 'blocoMSAprovacao{{ $medicaoServico->id }}','Medição de Serviço {{ $medicaoServico->id }}', {{ $medicaoServico->id }}, 'MedicaoServico', 'medicoes');"
                                        class="btn btn-danger btn-lg btn-flat">
                                    Reprovar
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
            </span>
            @endif
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('medicao_servicos.show_fields')

                    @include('medicoes.table')

                </div>
                <div class="row">
                    <a href="{!! route('medicoes.index') !!}" class="btn btn-default">
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