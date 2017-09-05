<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">
                Detalhes de Aprovações
                @if(count($alcadas_aprovacao['historicos']))
                    <button type="button" class="btn btn-xs pull-right btn-info" style="margin-right: 10px;"
                            onclick="$('.antesAlteracao').toggleClass('hidden');">
                        Exibir/ocultar histórico
                    </button>
                @endif
            </h4>
        </div>
        <div class="modal-body">
            @php
                $impressoAlteracao = false;
            @endphp
            @if(count($alcadas_aprovacao['atuais']))
                @foreach($alcadas_aprovacao['atuais'] as $array_alcada)
                    <h5>
                        Alçada {{ $array_alcada['alcada']->ordem }}
                    </h5>
                    @if(count($array_alcada['itens']))
                        @foreach($array_alcada['itens'] as $aprovacao)
                            <div class="box {{ is_null($aprovacao) ?  'box-default' : ($aprovacao->aprovado ? 'box-success' : 'box-danger') }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title box-title-full">
                                        <a href="#collapse-aprovacao-{{ $aprovacao->id }}" data-toggle="collapse">
                                            {{ $aprovacao->user->name }}
                                        </a>
                                        @if(is_null($aprovacao))
                                            <strong class="label label-default">
                                                Ainda não aprovou - Data Limite {{ $array_alcada['data_maxima'] }}
                                            </strong>
                                        @else
                                            @if($aprovacao->aprovado)
                                                <strong class="label label-success">
                                                    Aprovou: {{ $aprovacao->created_at->format('d/m/Y H:i') }}
                                                </strong>
                                            @else
                                                <strong class="label label-danger">
                                                    Reprovou: {{ $aprovacao->created_at->format('d/m/Y H:i') }}
                                                </strong>
                                            @endif

                                        @endif
                                    </h3>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-aprovacao-{{ $aprovacao->id }}">
                                    <div class="box-body">
                                        @if(!is_null($aprovacao))
                                            @if(!$aprovacao->aprovado)
                                                <div class="list-group-item">
                                                    <h4 class="list-group-item-heading">
                                                        Motivo Selecionado:
                                                    </h4>
                                                    <p class="list-group-item-text">
                                                        {{ $aprovacao->motivo->nome }}
                                                    </p>
                                                </div>
                                            @endif
                                            <div class="list-group-item">
                                                @if($aprovacao->aprovado)
                                                <h4 class="list-group-item-heading">
                                                    Observação:
                                                </h4>
                                                <p class="list-group-item-text">
                                                    {!! nl2br($aprovacao->justificativa) ?: 'Sem
                                                        Observação' !!}
                                                </p>
                                                @else
                                                    <h4 class="list-group-item-heading">
                                                        Justificativa:
                                                    </h4>
                                                    <p class="list-group-item-text">
                                                        {!! nl2br($aprovacao->justificativa) ?:
                                                            'Sem Justificativa' !!}
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <div class="list-group-item">
                                                <p class="list-group-item-text">
                                                    Usuário ainda não avaliou. Restam {{ $array_alcada['prazo'] }} dias
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if(count($array_alcada['falta']))
                        @foreach($array_alcada['falta'] as $user)
                            <div class="box box-default">
                                <div class="box-header with-border">
                                    <h3 class="box-title box-title-full">
                                        <a href="#collapse-user-aprovador-{{ $user['id'] }}" data-toggle="collapse">
                                            {{ $user['nome'] }}
                                        </a>

                                            <strong class="label label-default">
                                                Ainda não aprovou - Data Limite {{ $array_alcada['data_maxima'] }}
                                            </strong>
                                    </h3>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-user-aprovador-{{ $user['id'] }}">
                                    <div class="box-body">
                                        <div class="list-group-item">
                                            <p class="list-group-item-text">
                                                Restam {{ $array_alcada['prazo'] }} dia{{ ($array_alcada['prazo']>1)||($array_alcada['prazo'] < -1)?'s':''   }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if(!count($array_alcada['falta'])&&!count($array_alcada['itens']))
                        Item alterado antes de ter aprovadores,
                        <a href="{{ url('/workflow/redefinir?workflowTipo='.$workflow_tipo_id.'&id='.$id) }}" class="btn btn-success btn-flat">
                            <i class="fa fa-refresh"></i>
                            Redefinir aprovações
                        </a>
                    @endif
                @endforeach
            @else
                <h3>Não há aprovadores</h3>
            @endif
            @if(count($alcadas_aprovacao['historicos']))
                <h4 class="antesAlteracao hidden "><i class="fa fa-edit"></i> Nova rodada de aprovação iniciada em {{ $dataUltimoPeriodo->format('d/m/Y H:i') }}</h4>
                @foreach($alcadas_aprovacao['historicos'] as $array_alcada)
                    <h5 class="antesAlteracao hidden">
                        Alçada {{ $array_alcada['alcada']->ordem }}
                    </h5>
                    @if(count($array_alcada['itens']))
                        @foreach($array_alcada['itens'] as $aprovacao)
                            <div class="antesAlteracao hidden box {{ is_null($aprovacao) ?  'box-default' : ($aprovacao->aprovado ? 'box-success' : 'box-danger') }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title box-title-full">
                                        <a href="#collapse-aprovacao-{{ $aprovacao->id }}" data-toggle="collapse">
                                            {{ $aprovacao->user->name }}
                                        </a>
                                        @if(is_null($aprovacao))
                                            <strong class="label label-default">
                                                Ainda não aprovou - Data Limite {{ $array_alcada['data_maxima'] }}
                                            </strong>
                                        @else
                                            @if($aprovacao->aprovado)
                                                <strong class="label label-success">
                                                    Aprovou: {{ $aprovacao->created_at->format('d/m/Y H:i') }}
                                                </strong>
                                            @else
                                                <strong class="label label-danger">
                                                    Reprovou: {{ $aprovacao->created_at->format('d/m/Y H:i') }}
                                                </strong>
                                            @endif

                                        @endif
                                    </h3>
                                </div>
                                <div class="panel-collapse collapse" id="collapse-aprovacao-{{ $aprovacao->id }}">
                                    <div class="box-body">
                                        @if(!is_null($aprovacao))
                                            @if(!$aprovacao->aprovado)
                                                <div class="list-group-item">
                                                    <h4 class="list-group-item-heading">
                                                        Motivo Selecionado:
                                                    </h4>
                                                    <p class="list-group-item-text">
                                                        {{ $aprovacao->motivo->nome }}
                                                    </p>
                                                </div>
                                            @endif
                                            <div class="list-group-item">
                                                @if($aprovacao->aprovado)
                                                    <h4 class="list-group-item-heading">
                                                        Observação:
                                                    </h4>
                                                    <p class="list-group-item-text">
                                                        {!! nl2br($aprovacao->justificativa) ?: 'Sem
                                                            Observação' !!}
                                                    </p>
                                                @else
                                                    <h4 class="list-group-item-heading">
                                                        Justificativa:
                                                    </h4>
                                                    <p class="list-group-item-text">
                                                        {!! nl2br($aprovacao->justificativa) ?:
                                                            'Sem Justificativa' !!}
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <div class="list-group-item">
                                                <p class="list-group-item-text">
                                                    Usuário ainda não avaliou. Restam {{ $array_alcada['prazo'] }} dias
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
            </div>
        </div>
    </div>
</div>
