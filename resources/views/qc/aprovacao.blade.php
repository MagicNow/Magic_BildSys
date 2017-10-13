    @if(isset($workflowAprovacao))
        @if($workflowAprovacao['podeAprovar'])
            @if($workflowAprovacao['iraAprovar'])
                <span id="blocoItemAprovaReprova{{ $qc->id }}">
                    {{-- Used to save obs in local storage --}}
                    <input type="hidden" id="qc_id" value="{{ $qc->id }}">
                    <input type="hidden" id="user_id" value="{{ auth()->id() }}">
                    {{-- End of inputs used in local storage} --}}
                    <button type="button"
                        class="btn btn-lg btn-flat btn-primary"
                        data-toggle="modal"
                        data-target="#modal-obs">
                        Observação do Aprovador
                    </button>
                    <button type="button" onclick="workflowAprovaReprova({{ $qc->id }},
                        'Qc',1,'blocoItemAprovaReprova{{ $qc->id }}',
                        'Q.C. {{ $qc->id }}',0, '', '', true);"
                        class="btn btn-success btn-lg btn-flat"
                        title="Aprovar">
                        Aprovar Q.C.
                        <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <button type="button" onclick="workflowAprovaReprova({{ $qc->id }},
                        'Qc',0, 'blocoItemAprovaReprova{{ $qc->id }}',
                        'Q.C. {{ $qc->id }}',0, '', '', true);"
                        class="btn btn-danger btn-lg btn-flat"
                        title="Reprovar Este item">
                        Reprovar Q.C.
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
                                <textarea
                                    class="form-control"
                                    id="obs-aprovador"
                                    data-key="qc_obs_{{ auth()->id() }}_{{ $qc->id }}"
                                    rows="15"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="workflowAprovaReprova({{ $qc->id }},
                                    'Qc',1,'blocoItemAprovaReprova{{ $qc->id }}',
                                    'Q.C. {{ $qc->id }}',0, '', '', true);"
                                    class="btn btn-success btn-flat"
                                    title="Aprovar">
                                    Aprovar Q.C.
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <button type="button" onclick="workflowAprovaReprova({{ $qc->id }},
                                    'Qc',0, 'blocoItemAprovaReprova{{ $qc->id }}',
                                    'Q.C. {{ $qc->id }}',0, '', '', true);"
                                    class="btn btn-danger btn-flat"
                                    title="Reprovar Este item">
                                    Reprovar Q.C.
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @if($workflowAprovacao['jaAprovou'])
                    @if($workflowAprovacao['aprovacao'])
                        <span class="btn-lg btn-flat text-success"
                            title="Aprovado por você"
                            data-toggle="tooltip">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </span>
                    @else
                        <span class="text-danger btn-lg btn-flat"
                            title="Reprovado por você"
                            data-toggle="tooltip">
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
    @endif

<small class="label label-white pull-right margin10">
    <i class="fa fa-circle"
        aria-hidden="true"
        style="color:{{ $qc->status->cor }}"></i>
        {{ $qc->status->nome }}
</small>

