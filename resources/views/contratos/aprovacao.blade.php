@if($contrato->contrato_status_id < 4 )
    @if(isset($workflowAprovacao))
        @if($workflowAprovacao['podeAprovar'])
            @if($workflowAprovacao['iraAprovar'])
                <span id="blocoItemAprovaReprova{{ $contrato->id }}">
                    {{-- Used to save obs in local storage --}}
                    <input type="hidden" id="contrato_id" value="{{ $contrato->id }}">
                    <input type="hidden" id="user_id" value="{{ auth()->id() }}">
                    {{-- End of inputs used in local storage} --}}
                    <button type="button"
                        class="btn btn-lg btn-flat btn-primary"
                        data-toggle="modal"
                        data-target="#modal-obs">
                        Observação do Aprovador
                    </button>
                    <button type="button" onclick="workflowAprovaReprova({{ $contrato->id }},
                        'Contrato',1,'blocoItemAprovaReprova{{ $contrato->id }}',
                        'Contrato {{ $contrato->id }}',0, '', '', true);"
                        class="btn btn-success btn-lg btn-flat"
                        title="Aprovar">
                        Aprovar Contrato
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <button type="button" onclick="workflowAprovaReprova({{ $contrato->id }},
                        'Contrato',0, 'blocoItemAprovaReprova{{ $contrato->id }}',
                        'Contrato {{ $contrato->id }}',0, '', '', true);"
                        class="btn btn-danger btn-lg btn-flat"
                        title="Reprovar Este item">
                        Reprovar Contrato
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </span>
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
                                {{--<textarea class="form-control" id="obs-aprovador" data-set="obs_aprova_contrato" rows="15"></textarea>--}}
                                <textarea class="form-control" id="obs-aprovador" data-key="contrato_obs_{{ auth()->id() }}_{{ $contrato->id }}" rows="15"></textarea>
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
                    {{-- Não Aprovou ainda, pode aprovar, mas por algum motivo não irá aprovar no momento --}}
                    <button type="button" title="{{ $workflowAprovacao['msg'] }}"
                        onclick="swal('{{ $workflowAprovacao['msg'] }}','','info');"
                        class="btn btn-default btn-lg btn-flat">
                        <i class="fa fa-info" aria-hidden="true"></i>
                    </button>
                @endif
            @endif
        @endif
    @else
        
    @endif
    @if($contrato->status->nome == 'Reprovado')
        <a href="/contratos/{{$contrato->id}}/editar" type="button"
            class="btn btn-warning btn-lg btn-flat"
            title="Editar">
            Editar
            <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
        </a>
    @endif
@endif

@if($contrato->contrato_status_id == 4 )
    <a href="{{ route('contratos.imprimirContrato', $contrato->id) }}" target="_blank"
        class="btn btn-lg btn-flat btn-success" title="Imprimir Contrato para enviar ao Fornecedor">
        <i class="fa fa-print"></i>
    </a>
@endif
<small class="label label-default pull-right margin10">
    <i class="fa fa-circle"
        aria-hidden="true"
        style="color:{{ $contrato->status->cor }}"></i>
    {{ $contrato->status->nome }}
</small>

@if($contrato->contrato_status_id == 5 && $contrato->hasServico() )
    <a href="{{ Storage::url($contrato->arquivo) }}" download="contrato_{{ $contrato->id }}.pdf" target="_blank"
        class="btn btn-lg btn-flat btn-success pull-right" title="Imprimir Contrato assinado pelo fornecedor">
        <i class="fa fa-print"></i>
    </a>
@endif
