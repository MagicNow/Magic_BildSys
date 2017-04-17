<div class="col-md-12 loading">
    <h3>Relacionamento Insumos</h3>
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
                         {!! Form::select('servico_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'servico_id', 'disabled'=>'disabled', 'onchange'=>'selectgrupo(this.value, null, \'servicos\');']) !!}
                     </div>

                     <div class="col-md-3 col-md-offset-4">
                         <button type="submit" class="btn btn-primary">Adicionar relacionamento</button>
                     </div>

                 </div>
             </div>
         </div>
    </div>
</div>

<!-- Submit Field -->
{{--<div class="form-group col-sm-12">--}}
    {{--{!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}--}}
    {{--<a href="{!! route('admin.planejamentos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>--}}
{{--</div>--}}

@section('scripts')
    <script type="text/javascript">
        function selectgrupo(id, change, tipo){
            var rota = "{{url('/admin/planejamentos/grupos')}}/";
            if(tipo == 'servicos'){
                rota = "{{url('/admin/planejamentos/servicos')}}/";
            }
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option>Selecione</option>';
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
    </script>
@stop