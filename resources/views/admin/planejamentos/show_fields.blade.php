@section('content')
    <section class="content-header">
        <div class="modal-header">
            <div class="col-md-12">
                <span class="pull-left title">
                   <h3>
                       <button type="button" class="btn btn-link" onclick="history.go(-1);">
                           <i class="fa fa-arrow-left" aria-hidden="true"></i>
                       </button>
                       <span>{{$planejamento->tarefa}}</span>
                   </h3>
                </span>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')
        @include('adminlte-templates::common.errors')
        <style type="text/css">
            #carrinho ul{
                list-style-type: none;
                padding: 0px;
            }
            #carrinho ul li{
                background-color: #ffffff;
                border: solid 1px #dddddd;
                padding: 18px;
                margin-bottom: 12px;
                font-size: 16px;
                font-weight: 500;
                color: #9b9b9b;
            }
            #carrinho ul li .label-bloco{
                font-size: 13px;
                font-weight: bold;
                color: #4a4a4a;
                line-height: 15px;
                margin-bottom: 0px;
                padding-bottom: 0px;
            }
            .label-bloco-limitado{
                width: 72px;
            }
            @media (min-width: 769px){
                .label-bloco-limitado{
                    margin-top: -5px;
                }
            }
            .inputfile {
                width: 0.1px;
                height: 0.1px;
                opacity: 0;
                overflow: hidden;
                position: absolute;
                z-index: -1;
            }
            @media (min-width: 1215px){
                .margem-botao{
                    margin-top: -15px;
                }
            }

            .label-input-file{
                text-transform: none;
            }
            .dados-extras{
                background-color: #fff;
                margin-top: 20px;
            }
            .li-aberto{
                height: auto !important;
            }
            .col-xs-12, .col-xs-6, .col-xs-5, .col-xs-1{
                margin-bottom: 5px;
            }
            .btn-xs{
                overflow: hidden;
            }

        </style>
        <!-- Obra Id Field -->
        <div class="form-group col-md-6">
            {!! Form::label('obra_id', 'Obra:') !!}
            <p class="form-control">{!! $planejamento->obra->nome !!}</p>
        </div>

        <!-- Tarefa Field -->
        <div class="form-group col-md-6">
            {!! Form::label('tarefa', 'Tarefa:') !!}
            <p class="form-control">{!! $planejamento->tarefa !!}</p>
        </div>

        <!-- Data Field -->
        <div class="form-group col-md-6">
            {!! Form::label('data', 'Data Início:') !!}
            <p class="form-control">{!! $planejamento->data ? with(new\Carbon\Carbon($planejamento->data))->format('d/m/Y') : '' !!}</p>
        </div>

        <!-- Data Fim Field -->
        <div class="form-group col-md-6">
            {!! Form::label('data_fim', 'Data Fim:') !!}
            <p class="form-control">{!! $planejamento->data_fim ? with(new\Carbon\Carbon($planejamento->data_fim))->format('d/m/Y') : '' !!}</p>
        </div>

        <!-- Prazo Field -->
        <div class="form-group col-md-6">
            {!! Form::label('prazo', 'Prazo:') !!}
            <p class="form-control">{!! $planejamento->prazo !!}</p>
        </div>

        <!-- Resumo Field -->
        <div class="form-group col-md-6">
            {!! Form::label('resumo', 'Resumo:') !!}
            <p class="form-control">{!! strtoupper($planejamento->resumo) !!}</p>
        </div>
        <div class="row">
            <div id="carrinho" class="col-md-12">
                <ul>
                    @if(count($itens))
                        @foreach($itens as $item)
                            <li id="item_{{ $item->id }}">
                                <div class="row">
                                    <span class="pull-right text-center">
                                    <button type="button" class="btn btn-flat btn-link"
                                            style="font-size: 18px; margin-top: -7px"
                                            onclick="removePlanejamentoCompra({{ $item->id }})">
                                        <i class="text-red glyphicon glyphicon-trash" aria-hidden="true"></i>
                                    </button>
                                    </span>
                                    <table class="table table-hover">
                                        <tbody>
                                        <tr>
                                            <td>Grupo:</td>
                                            <td>{{$item->grupo->codigo}}</td>
                                            <td>{{$item->grupo->nome}}</td>
                                        </tr>
                                        <tr>
                                            <td>Subgrupo1:</td>
                                            <td>{{$item->subgrupo1->codigo}}</td>
                                            <td>{{$item->subgrupo1->nome}}</td>
                                        </tr>
                                        <tr>
                                            <td>Subgrupo2:</td>
                                            <td>{{$item->subgrupo2->codigo}}</td>
                                            <td>{{$item->subgrupo2->nome}}</td>
                                        </tr>
                                        <tr>
                                            <td>Subgrupo3:</td>
                                            <td>{{$item->subgrupo3->codigo}}</td>
                                            <td>{{$item->subgrupo3->nome}}</td>
                                        </tr>
                                        <tr>
                                            <td>Serviço:</td>
                                            <td>{{$item->servico->codigo}}</td>
                                            <td>{{$item->servico->nome}}</td>
                                        </tr>
                                        <tr>
                                            <td>Insumo:</td>
                                            <td>{{$item->insumo->codigo}}</td>
                                            <td>{{$item->insumo->nome}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <div class="text-center">
                            Nenhum item foi adicionado nesse planejamento!
                        </div>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="pg text-center">
        {{ $itens->links() }}
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

        function removePlanejamentoCompra(id) {
            swal({
                  title: "Remover este item?",
                  text: "",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Sim, remover!",
                  cancelButtonText: "Não",
                  closeOnConfirm: false
            },
            function(){
                startLoading();
                $.ajax("{{ url('admin/planejamentos/atividade/planejamentocompras') }}/"+ id, {}
                ).done(function (retorno) {
                    stopLoading();
                    if(retorno.success){
                        swal('Removido','', 'success');
                        $('#item_'+id).remove();
                    }else{
                        swal('Oops',retorno.error, 'error');
                    }
                }).fail(function (retorno) {
                    stopLoading();
                    error = 'Não foi possível remover o item';
                    swal("Oops" + error, "error");
                });
            });
        }
    </script>
@endsection

