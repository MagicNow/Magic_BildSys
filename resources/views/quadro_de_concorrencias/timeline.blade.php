<div class="panel panel-default panel-body">
    <h4 class="highlight">Timeline</h4>
    @if($alcadas_count)
        @php $col_md = 12 / ($alcadas_count + 1); @endphp
        <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                        <span>
                            Criação
                            <small>{{ $quadroDeConcorrencia->created_at->format('d/m/Y H:i') }}</small>
                        </span>
            <div class="progress">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    100%
                </div>
            </div>
        </h4>
        @if(count($avaliado_reprovado))
            @php
                $count = 0;
            @endphp
            @foreach($avaliado_reprovado as $alcada)
                @php
                    $count += 1;
                    $faltam_aprovar = $alcada['faltam_aprovar'];

                    if(count($faltam_aprovar) > 1){
                        $faltam_aprovar_texto = 'Faltam aprovar: ';
                    }else{
                        $faltam_aprovar_texto = 'Falta aprovar: ';
                    }

                    if(count($faltam_aprovar)){
                        foreach ($faltam_aprovar as $nome_falta){
                            $faltam_aprovar_texto .= $nome_falta.', ';
                        }
                    }
                    $faltam_aprovar_texto = substr($faltam_aprovar_texto,0,-2);
                @endphp
                @if($alcada['aprovadores'])
                    @if($alcada['total_avaliado'])
                        @php
                            $avaliado_aprovadores = $alcada['total_avaliado'] / $alcada['aprovadores'];
                            $percentual_quebrado = $avaliado_aprovadores / $qtd_itens;
                            $percentual = $percentual_quebrado * 100;
                            $percentual = number_format($percentual, 0);

                            if($percentual > 100){
                                $percentual = 100;
                            }
                        @endphp

                        <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                                        <span>
                                            {{$count}}ª alçada
                                            @if(isset($alcada['data_inicio']))
                                                <small>{{ $alcada['data_inicio'] }}</small>
                                            @endif
                                        </span>
                            @if($count == $alcadas_count)
                                <span class="pull-right">Finalizada</span>
                            @endif
                            <div class="progress" title="{{$faltam_aprovar_texto}}" data-toggle="tooltip" data-placement="top">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$percentual}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percentual}}%;">
                                    {{$percentual}}%
                                </div>
                            </div>
                        </h4>
                    @else
                        <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                            <span>{{$count}}ª alçada</span>
                            @if($count == $alcadas_count)
                                <span class="pull-right">Finalizada</span>
                            @endif
                            <div class="progress" title="{{$faltam_aprovar_texto}}" data-toggle="tooltip" data-placement="top">
                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; color: black;">
                                    0%
                                </div>
                            </div>
                        </h4>
                    @endif
                @else
                    <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                        <span>{{$count}}ª alçada</span>
                        @if($count == $alcadas_count)
                            <span class="pull-right">
                                            Finalizada
                                            <small>{{ $quadroDeConcorrencia->updated_at->format('d/m/Y H:i') }}</small>
                                        </span>
                        @endif
                        <div class="progress" title="Essa alçada não possuí aprovadores" data-toggle="tooltip" data-placement="top">
                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%; color: black;">
                                0%
                            </div>
                        </div>
                    </h4>
                @endif
            @endforeach
        @else
            @for($i = 1; $i <= $alcadas_count; $i ++)
                <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                    <span>{{$i}}ª alçada</span>
                    @if($i == $alcadas_count)
                        <span class="pull-right">{{$oc_status}}</span>
                    @endif
                    <div class="progress">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                            100%
                        </div>
                    </div>
                </h4>
            @endfor
        @endif
    @endif
</div>
