<div class="panel">
    <div class="panel-body">
        <h3 class="no-top-margin">
            Timeline <br>
            <small>
                Etapa atual: {{
                    $timeline['is_reproved']
                        ? 'Reprovado'
                        : $timeline['steps'][$timeline['current']]['name']
                }}
            </small>
        </h3>
        <ul class="new-timeline">
            @foreach($timeline['steps'] as $name => $step)
                <li class="new-timeline__item {{ $step['is_started'] ? 'new-timeline__item--complete' : '' }}">
                    <div class="new-timeline__timestamp">
                        @if($step['is_started'])
                            <span class="new-timeline__author">
                                @if($name === 'start')
                                    {{ $step['finished_by']->name }} criou Q.C.
                                @elseif($name === 'workflow')
                                    Workflow iniciado
                                @elseif($name === 'negociacao')
                                    {{ $timeline['is_reproved'] ? 'Reprovado' : 'Negociação em andamento' }}
                                @elseif($name === 'mobilizacao')
                                    {{ $timeline['is_reproved'] ? 'Reprovado' : 'Mobilização em andamento' }}
                                @endif
                            </span>
                            <span class="new-timeline__date">
                                Data planejada: {{ $step['end_date']->format('d/m/Y') }}
                            <span>
                            <span class="new-timeline__date">
                                Data realizada: {{ $step['is_finished']
                                ?  $step['finished_date']->format('d/m/Y') : 'Em processo' }}
                            <span>
                        @else
                            <span class="new-timeline__author">
                                {{ $timeline['is_reproved'] ? 'Reprovado' : 'Arguardando' }}
                            </span>
                            <span class="new-timeline__date">
                                Data planejada: {{ $step['end_date']->format('d/m/Y') }}
                            <span>
                            <span class="new-timeline__date">
                                Data realizada: {{ $step['is_finished'] ?  $step['finished_date']->format('d/m/Y') : 'Não iniciado' }}
                            <span>
                        @endif
                    </div>
                    <div class="new-timeline__status">
                        <h4 class="new-timeline__status-text">
                            {{ $step['name'] }}
                        </h4>
                    </div>
                </li>
                @if($name === 'workflow')
                    @foreach($step['timeline'] as $alcada)
                        <li class="new-timeline__item  new-timeline__item--sub {{ $alcada['is_finished'] ? 'new-timeline__item--complete' : '' }}">
                            <div class="new-timeline__timestamp">
                                <span class="new-timeline__author">
                                    @if(!$alcada['is_finished'])
                                        Aguardando aprovações
                                    @else
                                        {{ $alcada['is_approved'] ? 'Aprovado' : 'Reprovado' }}
                                    @endif
                                </span>
                                <span class="new-timeline__date">
                                    Data planejada: {{ $alcada['end_date']->format('d/m/Y') }}
                                <span>
                                <span class="new-timeline__date">
                                    @if($alcada['is_finished'])
                                        {{ $alcada['is_approved'] ? 'Aprovado em' : 'Reprovado em' }}
                                        {{ $alcada['finished_date']->format('d/m/Y') }}
                                    @else
                                        Aguardando aprovação
                                    @endif
                                <span>
                            </div>
                            <div class="new-timeline__status">
                                <h4 class="new-timeline__status-text">
                                    {{ $alcada['name'] }}
                                </h4>
                            </div>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
</div>
