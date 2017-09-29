<!-- Mascara Padrao Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('mascara_padrao_id', 'Mascara Padrao Id:') !!}
    {!! Form::select('mascara_padrao_id',[''=>'Escolha...']+$mascaras, null, ['class' => 'form-control select2', 'required'=>true]) !!}
</div>

<div class="form-group col-sm-12">
    <div class="col-md-8">
        <div class="input-group">
            <span class="input-group-addon">{{$grupo->codigo}}</span>
            <p class="form-control">{{ $grupo->nome }}</p>
        </div>
    </div>
    <div class="col-md-4">
        {!! Form::hidden('estrutura[0][id]', $grupo->id, ['id'=>'select_grupo_0_select', 'class'=>'estrutura_0']) !!}
        <button type="button" class="btn btn-primary" onclick="addSubItem('select_grupo_0', 0, 'estrutura[0][itens]')">Add SubGrupo-1</button>
    </div>
    <ul id="select_grupo_0_ul">
    </ul>
</div>



<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.mascaraPadraoEstruturas.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        var blocos = 0;
        var insumos = 0;
        var bloco_aberto = true;

        $( "form" ).submit(function( event ) {
            if(bloco_aberto){
                event.preventDefault();
                swal('Existe um bloco em aberto','', 'error');
                $('.overlay').remove();

            }
        });

        function addSubItem(id_atual, nivel, nome){
            var buttonAdd = '';
            bloco_aberto = true;
            blocos++;
            var rota = "{{url('/admin/mascaraPadraoEstruturas/grupos')}}/";
            nivel++;
            var labelBotao = 'Add SubGrupo-'+(nivel+1);
            if(nivel == 3){
                labelBotao = 'Add Serviço';
            }else if(nivel>3){
                rota = "{{url('/admin/mascaraPadraoEstruturas/servicos')}}/";
            }
            if(nivel < 4){
                buttonAdd = '<button type="button" class="btn btn-primary" onclick="addSubItem(\'subgrupo'+nivel+'_'+blocos+'\', '+nivel+',\''+nome+'['+blocos+'][itens]'+'\')">'+labelBotao+'</button>';
            }
            if(nivel == 4){
                labelBotao = 'Add Insumos';
                buttonAdd = '<button type="button" class="btn btn-warning" onclick="addInsumo(\'subgrupo'+nivel+'_'+blocos+'\', '+nivel+',\''+nome+'['+blocos+'][insumos]'+'\')">'+labelBotao+'</button>';
            }

            console.log(nivel);
            var id = $('#'+id_atual+'_select').val();
            if(id){

                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option value="">Selecione</option>';
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });

                    selectHTML = '' +
                        '<div class="col-md-12" style="padding: 5px;">' +
                        '<div class="col-md-8">' +
                        '<li style="list-style-type: none" bloco="'+blocos+'" id="subgrupo'+nivel+'_'+blocos+'">' +
                        '<div class="input-group">' +
                        '<span class="input-group-addon"></span>' +
                        '<select class="form-control select2 estrutura_'+nivel+'" onchange="percorreBloco()" name="'+nome+'['+blocos+'][id]" id="subgrupo'+nivel+'_'+blocos+'_select">' +
                        options +
                        '</select>' +
                        '</div>' +
                        '</li>' +
                        '</div>' +
                        '<div class="col-md-1">' +
                        '<button type="button" class="btn btn-primary" onclick="">Novo</button>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        buttonAdd +
                        '</div>' +
                        '<ul id="subgrupo'+nivel+'_'+blocos+'_ul">'+
                        '</ul>'+
                        '</div>';
                    $('#'+id_atual+'_ul').append(selectHTML);
                    $('.overlay').remove();
                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }

        // Função que vai relacionar a estrutura com o insumo.
        function addInsumo(id_atual, nivel, nome){
            insumos++;
            console.log(id_atual, nivel, nome);
            $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            $.ajax('{{route('admin.mascaraPadraoEstruturas.insumos')}}')
            .done(function(retorno){
                options = '<option value="">Selecione</option>';
                $.each(retorno,function(index, value){
                    options += '<option value="'+index+'">'+value+'</option>';
                });

                selectHTML = '' +
                    '<div class="col-md-12" style="padding: 5px;">' +
                    '<div class="col-md-8">' +
                    '<li style="list-style-type: none" id="insumo'+nivel+'_'+insumos+'">' +
                    '<div class="input-group">' +
                    '<span class="input-group-addon"></span>' +
                    '<select class="form-control select2" name="'+nome+'['+insumos+']" id="insumo'+nivel+'_'+insumos+'_select">' +
                    options +
                    '</select>' +
                    '</div>' +
                    '</li>' +
                    '</div>' +
                    '<ul id="subgrupo'+nivel+'_'+insumos+'_ul">'+
                    '</ul>'+
                    '</div>';

                $('#'+id_atual+'_ul').append(selectHTML);
                $('.overlay').remove();
            })
            .fail(function (retorno) {
                $('.overlay').remove();
            });
        }

        function percorreBloco() {
            bloco_aberto = false;
            if(!$('.estrutura_0').length){
                bloco_aberto = true;
                return false;
            }
            $('.estrutura_0').each(function (idx) {
                if(!$(this).val()){
                    bloco_aberto = true;
                    return false;
                }
                if(!$('.estrutura_1').length){
                    bloco_aberto = true;
                    return false;
                }
                $('.estrutura_1').each(function (idx) {
                    if(!$(this).val()){
                        bloco_aberto = true;
                        return false;
                    }
                    if(!$('.estrutura_2').length){
                        bloco_aberto = true;
                        return false;
                    }
                    $('.estrutura_2').each(function (idx) {
                        if(!$(this).val()){
                            bloco_aberto = true;
                            return false;
                        }
                        if(!$('.estrutura_3').length){
                            bloco_aberto = true;
                            return false;
                        }
                        $('.estrutura_3').each(function (idx) {
                            if(!$(this).val()) {
                                bloco_aberto = true;
                                return false;
                            }
                            if(!$('.estrutura_4').length){
                                bloco_aberto = true;
                                return false;
                            }
                            $('.estrutura_4').each(function (idx) {
                                if(!$(this).val()) {
                                    bloco_aberto = true;
                                    return false;
                                }
                            });
                        });
                    });
                });
            });
        }
    </script>
@stop
