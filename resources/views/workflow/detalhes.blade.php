<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Alçada de aprovação #{{ $alcada->ordem }}</h4>
        </div>
        <div class="modal-body">
            @foreach($alcada->users as $user)
                @php
                    $aprovacao = $aprovacoes->where('user_id', $user->id)->first();
                @endphp
                <div class="box {{ is_null($aprovacao) ?  'box-default' : ($aprovacao->aprovado ? 'box-success' : 'box-danger') }}">
                    <div class="box-header with-border">
                        <h3 class="box-title box-title-full">
                            <a href="#collapse-user-{{ $user->id }}" data-toggle="collapse">
                                {{ $user->name }}
                            </a>
                            @if(is_null($aprovacao))
                                <strong class="label label-default">
                                    Ainda não aprovou
                                </strong>
                            @else
                                @if($aprovacao->aprovado)
                                    <strong class="label label-success">
                                        Aprovou: {{ $aprovacao->created_at->format('d/m/Y') }}
                                    </strong>
                                @else
                                    <strong class="label label-danger">
                                        Reprovou: {{ $aprovacao->created_at->format('d/m/Y') }}
                                    </strong>
                                @endif
                            @endif
                        </h3>
                    </div>
                    <div class="panel-collapse collapse" id="collapse-user-{{ $user->id }}">
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
                                        Usuário ainda não avaliou
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>
