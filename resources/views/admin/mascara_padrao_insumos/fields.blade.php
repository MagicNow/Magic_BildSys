<div class="col-md-12 loading">
    <h3>Relacionamento de Máscara Padrão / Insumos</h3>
    <div class="col-md-12 thumbnail">
        <div class="col-md-12">
            <div class="caption">
                <div class="card-description">
                    <!-- Máscara Padrão Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('mascara_padrao_id', 'Máscara Padrão:') !!}
                        {!! Form::select('mascara_padrao_id', [''=>'-']+$mascaraPadrao, null, ['class' => 'form-control select2', 'id'=>'mascara_padrao_id', 'required'=>'required']) !!}
                    </div>

                    <!-- Grupo de insumos Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('grupo_insumo_id', 'Grupo de insumos:') !!}
                        {!! Form::select('grupo_insumo_id', [''=>'-']+$grupoInsumos, null, ['class' => 'form-control select2', 'id'=>'grupo_insumo_id', 'required'=>'required', 'onchange'=>'insumos(this.value)']) !!}
                    </div>
					
					<!-- Coeficiente Field -->
					<div class="form-group col-sm-3">
						{!! Form::label('coeficiente', 'Coeficiente Padrão:') !!}
						{!! Form::text('coeficiente', null, ['class' => 'form-control', 'required']) !!}
					</div>

                    <div id="insumos" class="col-md-12"></div>					

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
                        <a href="{!! route('admin.mascara_padrao_insumos.index') !!}" class="btn btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        function insumos(id){
            var rota = "{{url('/admin/mascara_padrao_insumos/insumos')}}/";
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    $('#insumos').css('display','');
                    list = '';
                    if(retorno.length > 0) {
                        list += '<h3>Insumos</h3><li><input type="checkbox" id="checkAll"> <label>Selecionar todos</label></li>\
                                </div>';
                        $.each(retorno,function(index, value){
                            list += '<li><input type="checkbox" class="insumos" id="insumo_'+ value.id +'" name="insumo_id[]" value="'+ value.id +'"> <label for="insumo_'+ value.id +'">' + value.nome + '</label></li>\
                                </div>';
                        });

                        $('#insumos').html('<ul style="list-style: none">' +list+ '</ul>');

                        $('#checkAll').on('ifChanged', function (event) {
                            $(".insumos").prop('checked', $(event.target).prop("checked"));
                            $("input").iCheck('update');
                        });
                    }else{
                        list = 'Não tem insumos nesse grupo!';
                        $('#insumos').html('<ul style="list-style: none">' +list+ '</ul>');
                    }
                    $('input').iCheck({
                        checkboxClass: 'icheckbox_square-green',
                        radioClass: 'iradio_square-green',
                        increaseArea: '20%' // optional
                    });
                    $('.overlay').remove();
                }).fail(function() {
                    $('.overlay').remove();
                });
            }else{
                $('#insumos').css('display','none');
            }
        }
    </script>
@stop
