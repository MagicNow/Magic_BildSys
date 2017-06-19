@inject('carbon', 'Carbon\Carbon')
@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Detalhes do Contrato #{{ $contrato->id . ' Obra '.$contrato->obra->nome }}
            @if($contrato->contrato_status_id < 4 )
                @if(isset($workflowAprovacao))
                    @if($workflowAprovacao['podeAprovar'])
                        @if($workflowAprovacao['iraAprovar'])
                            <span class="text-warning"> ||  Aprovação de Contrato </span>
                            <div class="btn-group" role="group" id="blocoItemAprovaReprova{{ $contrato->id }}"
                                 aria-label="...">
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
                                    Reprovar
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
                <a href="{{ route('contratos.imprimirContrato', $contrato->id) }}" download="contrato_{{ $contrato->id }}.pdf" target="_blank"
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
        </h1>
    </section>

    <div class="content">
        @if($contrato->contrato_status_id == 4 || (is_null($contrato->arquivo) && $contrato->contrato_status_id == 5)  )
        {!! Form::open(['url'=>'/contratos/'.$contrato->id.'/envia-contrato', 'files'=> true ]) !!}
            <div class="box box-warning">
                <div class="box-header with-border">
                    Enviar contrato assinado
                </div>
                <div class="box-body">
                    <div class="col-md-10">
                        {!! Form::file('arquivo',['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-flat btn-success btn-block"><i class="fa fa-upload"></i> Enviar {{ $contrato->contrato_status_id == 4? ' e Liberar':'' }}</button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        @endif

        <div class="row">
            <div class="col-sm-4">
                <div class="box box-muted">
                    <div class="box-header with-border">
                        Detalhes do Fornecedor
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Nome</th>
                                <td>{{ $contrato->fornecedor->nome }}</td>
                            </tr>
                            <tr>
                                <th>CNPJ</th>
                                <td>{{ $contrato->fornecedor->cnpj }}</td>
                            </tr>
                            <tr>
                                <th>Telefone</th>
                                <td>{!! $contrato->fornecedor->telefone ?: '<span class="text-danger">Sem telefone</span>'  !!}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{!! $contrato->fornecedor->email ?: '<span class="text-danger">Sem email</span>' !!} </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-muted">
                    <div class="box-header with-border">
                        Sumarização
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>Valor Inicial</th>
                                <td>{{ float_to_money($valor_inicial) }}</td>
                            </tr>
                            <tr>
                                <th>Valor Atual</th>
                                <td>{{ float_to_money($contrato->valor_total) }}</td>
                            </tr>
                            <tr>
                                <th>Valor Medido</th>
                                <td>{{ float_to_money(0.00) }}</td>
                            </tr>
                            <tr>
                                <th>Saldo</th>
                                <td>{{ float_to_money($contrato->valor_total) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default panel-body">
                    <h4 class="highlight">Timeline</h4>
                    @if($alcadas_count)
                        @php $col_md = 12 / ($alcadas_count + 1); @endphp
                        <h4 class="col-md-{{$col_md}} col-sm-{{$col_md}}" style="padding-right: 1px;padding-left: 1px;">
                            <span>
                                Criação
                                <small>{{ $contrato->created_at->format('d/m/Y H:i') }}</small>
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
                                                <small>{{ $contrato->updated_at->format('d/m/Y H:i') }}</small>
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
                                        <span class="pull-right">{{ $status }}</span>
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
            </div>
        </div>
        <div class="box box-muted">
            <div class="box-body">
                @include('contratos.table')
            </div>
        </div>
        @if(!$pendencias->isEmpty())
            @include('contratos.box-pendencias')
        @endif
    </div>

    <div class="hidden">
        {!! Form::select('motivo', $motivos, null, ['id' => 'motivo']) !!}
    </div>

    <div class="content">
        <a href="{!! route('contratos.index') !!}" class="btn btn-default btn-flat btn-lg">
            <i class="fa fa-arrow-left"></i> {{ ucfirst( trans('common.back') )}}
        </a>
    </div>

    <div class="modal centered-modal fade" id="modal-reapropriar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Reapropriar</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group js-ajax-container">
                    </div>
                    @include('partials.grupos-de-orcamento', ['full' => true])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-success btn-flat js-save">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal centered-modal fade" id="modal-reajuste">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Reajuste</h4>
                </div>
                <div class="modal-body js-ajax-container">
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger btn-flat"
                        data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button"
                        class="btn btn-success btn-flat js-save">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal centered-modal fade" id="modal-distrato" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Distrato</h4>
                </div>
                <div class="modal-body js-ajax-container">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success btn-flat js-save">Salvar</button>
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="modal centered-modal fade" id="modal-editar" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Editar Aditivo</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="qtd">Quantidade</label>
            {!! Form::text('qtd', null, ['class' => 'form-control money']) !!}
          </div>
          <div class="form-group">
            <label for="valor">Valor</label>
            <div class="input-group">
              <span class="input-group-addon">R$</span>
              {!! Form::text('valor', null, ['class' => 'form-control money']) !!}
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">
            Cancelar
          </button>
          <button type="button" class="btn btn-success btn-flat js-save">
            Salvar
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
    <script> options_motivos = document.getElementById('motivo').innerHTML; </script>
    <script data-token="{{ csrf_token() }}" src="{{ asset('/js/contrato-actions.js') }}"></script>
@append
