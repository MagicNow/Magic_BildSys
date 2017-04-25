@include('flash::message')
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

@section('scripts')
    <script type="text/javascript">
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

    </script>
@stop