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
    <h3>Relacionamento de comprador / insumos</h3>
    <div class="col-md-12 thumbnail">
        <div class="col-md-12">
            <div class="caption">
                <div class="card-description">
                    <!-- User Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('usuario_id', 'Usuários:') !!}
                        {!! Form::select('usuario_id', [''=>'-']+$users, null, ['class' => 'form-control select2', 'id'=>'usuario_id', 'required'=>'required']) !!}
                    </div>

                    <!-- Grupo de insumos Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('grupo_insumo_id', 'Grupo de insumos:') !!}
                        {!! Form::select('grupo_insumo_id', [''=>'-']+$grupoInsumos, null, ['class' => 'form-control select2', 'id'=>'grupo_insumo_id', 'required'=>'required', 'onchange'=>'insumos(this.value)']) !!}
                    </div>

                    <div id="insumos" class="col-md-12"></div>

                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
                        <a href="{!! route('admin.compradorInsumos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        function insumos(id){
            var rota = "{{url('/admin/compradorInsumos/insumos')}}/";
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
