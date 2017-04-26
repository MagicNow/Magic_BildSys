@include('flash::message')
<style type="text/css">
    #carrinho ul{
        list-style-type: none;
        padding: 0px;
    }
    #carrinho ul li{
        background-color: #ffffff;
        border: solid 1px #dddddd;
        padding: 5px;
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
    #carrinho li button {
        text-align: left !important;
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
                @foreach($itens as $item)
                <li>
                    <button type="button" class="btn btn-link btn-block text-left" onclick="listInsumosRelacionados({{$item->grupo_id}}, {{$planejamento->id}}, 'subgrupo1_id', 'grupo_id')">{{$item->codigo .' - '. $item->nome}} <i class="fa fa-plus-square pull-right" aria-hidden="true"></i></button>
                    <ul id="obj_{{$item->grupo_id}}">

                    </ul>
                </li>
                @endforeach
            </ul>
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

        function listInsumosRelacionados(obj_id, planejamento_id, tipo, campo){
            $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            if(obj_id && planejamento_id){
                $.ajax("{{ url('/admin/planejamentos/atividade/servico/insumo/relacionados') }}", {
                            data: {
                                'id' : obj_id,
                                'campo': campo,
                                'planejamento_id' : planejamento_id,
                                'tipo' : tipo
                            },
                            type: "GET"
                        }
                ).done(function(retorno) {
                    list = '';
                    $.each(retorno,function(index, value){
                        if(value.atual!='insumo_id'){
                            list += '<li><button type="button" class="btn btn-link btn-block text-left" ' +
                                    'onclick="listInsumosRelacionados('+value.id+', {{$planejamento->id}}, \''+
                                    value.proximo+'\', \''+value.atual+'\')">'+value.codigo+' - '+value.nome+
                                    ' <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>' +
                                    '</button>'+
                                    '<ul id="obj_'+value.id+'"></ul></li>';
                        }else{
                            list += '<li id="item_'+value.id+'">'+value.codigo+' - '+value.nome+
                                    '<button type="button" class="btn btn-flat btn-link pull-right" style="font-size: 18px; margin-top: -7px" onclick="removePlanejamentoCompra('+value.id+')">'+
                                    '<i class="text-red glyphicon glyphicon-trash" aria-hidden="true"></i>'+
                                    '</button>' +
                                    '</li>';
                        }

                    });
                    $('#obj_'+ obj_id).html(list);
                    $('.overlay').remove();

                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }


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