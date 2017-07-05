@if($entrega->se_status_id === 1)
    @if(isset($workflowAprovacao))
        @if($workflowAprovacao['podeAprovar'])
            @if($workflowAprovacao['iraAprovar'])
                <span id="blocoItemAprovaReprova{{ $entrega->id }}">
                    <button type="button" onclick="workflowAprovaReprova({{ $entrega->id }},
                        'SolicitacaoEntrega',1,'blocoItemAprovaReprova{{ $entrega->id }}',
                        'Solicitação #{{ $entrega->id }}',0, '', '', true);"
                        class="btn btn-success btn-lg btn-flat"
                        title="Aprovar">
                        Aprovar Solicitação
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <button type="button" onclick="workflowAprovaReprova({{ $entrega->id }},
                        'SolicitacaoEntrega',0, 'blocoItemAprovaReprova{{ $entrega->id }}',
                        'Solicitação #{{ $entrega->id }}',0, '', '', true);"
                        class="btn btn-danger btn-lg btn-flat"
                        title="Reprovar Este item">
                        Reprovar Solicitação
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </span>
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
        @if($aprovado)
            <span class="btn-lg btn-flat text-success" title="Aprovado">
                <i class="fa fa-check" aria-hidden="true"></i>
            </span>
        @else
            <span class="text-danger btn-lg btn-flat" title="Reprovado">
                <i class="fa fa-times" aria-hidden="true"></i>
            </span>
        @endif
    @endif
@endif
