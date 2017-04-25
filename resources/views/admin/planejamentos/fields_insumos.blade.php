@include('flash::message')
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
    @media (min-width: 769px){
        .label-bloco-limitado{
            margin-top: -5px;
        }
    }
    @media (min-width: 1215px){
        .margem-botao{
            margin-top: -15px;
        }
    }
    .tooltip-inner {
        max-width: 500px;
        text-align: left !important;
    }
</style>
<div class="col-md-12 loading">
    <h3>Planejamento de compras</h3>
    <div class="col-md-12 thumbnail">
         <div class="col-md-12">
             <div class="caption">
                 <div class="card-description">
                     <!-- Grupos de insumo Field -->
                     <div class="form-group col-sm-12">
                         {!! Form::label('grupo_id', 'Grupos:') !!}
                         {!! Form::select('grupo_id', [''=>'-']+$grupos, null, ['class' => 'form-control', 'id'=>'grupo_id','onchange'=>'selectgrupo(this.value, \'subgrupo1_id\', \'grupos\');']) !!}
                     </div>

                     <!-- SubGrupos1 de insumo Field -->
                     <div class="form-group col-sm-12">
                         {!! Form::label('subgrupo1_id', 'SubGrupo-1:') !!}
                         {!! Form::select('subgrupo1_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo1_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo2_id\', \'grupos\');']) !!}
                     </div>

                     <!-- SubGrupos2 de insumo Field -->
                     <div class="form-group col-sm-12">
                         {!! Form::label('subgrupo2_id', 'SubGrupo-2:') !!}
                         {!! Form::select('subgrupo2_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo2_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'subgrupo3_id\', \'grupos\');']) !!}
                     </div>

                     <!-- SubGrupos3 de insumo Field -->
                     <div class="form-group col-sm-12">
                         {!! Form::label('subgrupo3_id', 'SubGrupo-3:') !!}
                         {!! Form::select('subgrupo3_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'subgrupo3_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, \'servico_id\', \'servicos\');']) !!}
                     </div>

                     <!-- SubGrupos4 de insumo Field -->
                     <div class="form-group col-sm-12">
                         {!! Form::label('servico_id', 'Serviço:') !!}
                         {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\'), listInsumos(this.value);']) !!}
                     </div>
                     <input type="hidden" name="planejamento_id" value="{{$planejamento->id}}">

                     <div class="col-md-12" id="list-insumos"></div>

                     <div class="col-md-4 col-md-offset-4">
                         <button type="submit" class="btn btn-primary btn-lg">Adicionar relacionamentos</button>
                     </div>
                 </div>
             </div>
         </div>
    </div>
</div>
    @if(isset($itens))
    <div id="carrinho" class="col-md-12">
        <h3>Insumos relacionados</h3>
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
                    Serviço:
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
</div>
        <div class="pg text-center">
            {{ $itens->links() }}
        </div>
        @endif

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('div.alert').not('.alert-important').delay(10000).fadeOut(350);

        function selectgrupo(id, change, tipo){
            var rota = "{{url('/admin/planejamentos/atividade/grupos')}}/";
            if(tipo == 'servicos'){
                rota = "{{url('/admin/planejamentos/atividade/servicos')}}/";
            }
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option value="">Selecione</option>';
                    $('#'+change).html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#'+change).html(options);
                    $('.overlay').remove();
                    $('#'+change).attr('disabled',false);
                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }

        function listInsumos(id){
            console.log(id);
            rota = "{{url('/admin/planejamentos/atividade/servico/insumo')}}/";
            if(id){
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    list = '<label>Insumos</label>';
                    list += '<li><input type="checkbox" id="checkAll"> <label>Selecionar todos</label></li>\
                                </div>';
                    $.each(retorno,function(index, value){
                        list += '<li><input type="checkbox" id="insumo_'+ value.id +'" name="insumos[]" value="'+ value.id +'"> <label for="insumo_'+ value.id +'">' + value.codigo + ' - ' + value.nome + '</label></li>\
                                </div>';
                    });
                    $('#list-insumos').html('<ul style="list-style: none">'+list+'</ul>');
                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }

        $(document).ready(function() {
            $('#list-insumos').on('click','#checkAll', function () {
                $("input:checkbox").prop('checked', $(this).prop("checked"));
            });
        });

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
@stop