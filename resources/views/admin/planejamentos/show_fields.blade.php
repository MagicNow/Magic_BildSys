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
            .tooltip-inner {
                max-width: 500px;
                 text-align: left !important;
            }
            /*.tooltip-ajuste {*/
                /*max-width: 500px;*/
            /*}*/

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
            {!! Form::label('data', 'Data InÃ­cio:') !!}
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
                @if(request()->get('carteira_avulsa'))
                    {!! Form::hidden('carteira_avulsa',1) !!}
                    <div class="form-group col-sm-12">
                        {!! Form::label('planejamentoQcAvulsoCarteira', 'Carteiras de Q.C. Avulso:') !!}
                        <ul class="list-group">
                            @if($planejamento->planejamentoQcAvulsoCarteira)
                                @foreach($planejamento->planejamentoQcAvulsoCarteira as $qcAvulsoCarteira)
                                    <li class="list-group-item">{{ $qcAvulsoCarteira->nome }}</li>
                                @endforeach
                            @endif
                        </ul>

                    </div>
                @else
                <ul>
                    @if(count($itens))
                        <?php $servico = null ?>
                        @foreach($itens as $item)
                            @if($servico != $item->servico_id)
                                @if($servico > 0)
                                    <!-- fecha tabela -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </li>
                                @endif
                                <?php $servico = $item->servico_id?>
                                <!-- cria tabela -->
                                <li>
                                    <div class="row">
                                        <table class="table table-hover">
                                            <tbody>
                                                <h4>
                                                    ServiÃ§o:
                                                    <span data-toggle="tooltip" data-placement="top" data-html="true" title="
                                                        {{$item->grupo->codigo.' - '.$item->grupo->nome}}<br/>
                                                        {{$item->subgrupo1->codigo.' - '.$item->subgrupo1->nome}}<br/>
                                                        {{$item->subgrupo2->codigo.' - '.$item->subgrupo2->nome}}<br/>
                                                        {{$item->subgrupo3->codigo.' - '.$item->subgrupo3->nome}}<br/>
                                                        {{$item->servico->codigo.' - '.$item->servico->nome}}">
                                                        <strong>{{$item->servico->codigo}}</strong>
                                                    </span>
                                                </h4>
                            @endif
                            <tr id="item_{{ $item->id }}">
                                <td>{{$item->insumo->codigo}}</td>
                                <td>{{$item->insumo->nome}}</td>
                                <td>
                                    <span class="pull-right text-center">
                                         <button type="button" class="btn btn-flat btn-link"
                                                 style="font-size: 18px; margin-top: -7px"
                                                 onclick="removePlanejamentoCompra({{ $item->id }})">
                                             <i class="text-red glyphicon glyphicon-trash" aria-hidden="true"></i>
                                         </button>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        @if($servico)
                                    </tbody>
                                </table>
                            </div>
                        </li>
                        @endif
                    @else
                        <div class="text-center">
                            Nenhum item foi adicionado nesse planejamento!
                        </div>
                    @endif
                </ul>
            @endif
            </div>
        </div>

    <div class="pg text-center">
        {{ $itens->links() }}
    </div>

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

        function removePlanejamentoCompra(id) {
            swal({
                  title: "Remover este item?",
                  text: "",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "Sim, remover!",
                  cancelButtonText: "NÃ£o",
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
                    error = 'NÃ£o foi possÃ­vel remover o item';
                    swal("Oops" + error, "error");
                });
            });
        }
    </script>
@endsection
