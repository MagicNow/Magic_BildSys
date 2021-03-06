@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            <button type="button" class="btn btn-link" onclick="history.go(-1);">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            Lista de inconsistências
        </h1>
    </section>
    <div class="content">
        <div class="form-group col-md-3">
            {!! Form::label('requisicao', 'Requisição Nro:') !!}
            <p class="form-control">{!! $requisicao->id !!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('status', 'Status:') !!}
            <p class="form-control">{!! $requisicao->status->nome !!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('data', 'Data:') !!}
            <p class="form-control">{!! $requisicao->created_at->format('d/m/Y') !!}</p>
        </div>

        <div class="form-group col-md-3">
            {!! Form::label('solicitante', 'Solicitante:') !!}
            <p class="form-control">{!! $requisicao->user->name !!}</p>
        </div>

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="insumos-table" class="table table-striped table-responsive">
                    <thead>
                    <tr align="left">
                        <th>Agrupamento</th>
                        <th>Insumo</th>
                        <th>Unidade de medida</th>
                        <th>Qtd solicitada</th>
                        <th>Qtd lida</th>
                        <th>Número de leituras</th>
                        <th>Inconsistência</th>
                        <th>Ações</th>
                    </tr>
                    </thead>

                    <tbody id="body-insumos-table">
                        @foreach($requisicao_itens as $item)
                            <tr align="left">
                                <td>{{$item->agrupamento}}</td>
                                <td>{{$item->insumo}}</td>
                                <td>{{$item->unidade_medida}}</td>
                                <td>{{$item->qtd_solicitada}}</td>
                                <td>{{$item->qtd_lida?:'0,00'}}</td>
                                <td>{{$item->numero_leituras}}</td>
                                <td>
                                    <span style="color:{{$item->inconsistencia == 'OK' ? '#7ed321' : "#eb0000"}}">{{$item->inconsistencia}}</span>
                                </td>
                                <td>
                                @if($item->numero_leituras)
                                    <a onclick="excluirLeitura({{$item->id}});" title="Excluir leitura" class='btn btn-default'>
                                        Excluir leitura
                                    </a>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-12">
                <a href="{{ route('requisicao.processoSaida', $requisicao->id) }}" class="btn btn-warning pull-right">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Finalizar leitura de saída
                </a>

                <a href="{{ route('requisicao.lerInsumoSaida', $requisicao->id) }}" class="btn btn-success pull-right">
                    <i class="fa fa-qrcode" aria-hidden="true"></i> Continuar leitura de saída
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function excluirLeitura(requisicao_id) {

            swal({
                title: "{{ ucfirst( trans('common.are-you-sure') ) }}?",
                text: "{{ ucfirst( trans('common.you-will-not-be-able-to-recover-this-registry') ) }}!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ ucfirst( trans('common.yes') ) }}, {{ ucfirst( trans('common.delete') ) }}!",
                cancelButtonText: "{{ ucfirst( trans('common.cancel') ) }}"
            }, function () {
                $.ajax('{{ route('requisicao.excluirLeitura') }}', {
                    data: {
                        requisicao_id: requisicao_id
                    }
                }).done(function () {
                    location.reload();
                });
            });
        }
    </script>
@endsection