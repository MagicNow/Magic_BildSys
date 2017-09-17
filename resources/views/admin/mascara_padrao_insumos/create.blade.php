@extends('layouts.app')

@section('content')
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.mascara_padrao_insumos.store']) !!}

                        @include('admin.mascara_padrao_insumos.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection


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
