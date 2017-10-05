<!-- Mascara Padrao Id Field -->
<div class="form-group col-sm-12">
    {!! Form::label('mascara_padrao_id', 'Máscara Padrão:') !!}
    {!! Form::select('mascara_padrao_id',[''=>'Escolha...']+$mascaras, (isset($mascaraPadrao) ?$mascaraPadrao->id : null), ['class' => 'form-control select2', 'required'=>true]) !!}
</div>

<div class="form-group col-sm-12">
    @if(count($mascaraPadraoEstruturas))
        @php
            $count = 0;
        @endphp
        <div class="col-md-8" style="padding: 5px;">
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
        @foreach($subgrupos1 as $subgrupo1)
            @if($subgrupo1->grupo_id == $grupo->id)
                    @php
                        $count++;
                    @endphp
                <li style="list-style-type: none" id="subgrupo1_{{$count}}">
                    <div class="col-md-8" style="padding: 5px;">
                        <div class="input-group">
                            <span class="input-group-addon">{{$subgrupo1->codigo}}</span>
                            {!! Form::select('estrutura[0][itens]['.$count.'][id]',[''=>'Escolha...']+$selectSubgrupos1, $subgrupo1->id, ['id'=>'subgrupo1_'.$count.'_select','class' => 'form-control select2 estrutura_1', 'onchange'=>'percorreBloco()']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary" onclick="addSubItem('subgrupo1_{{$count}}', 1, 'estrutura[0][itens][{{$count}}][itens]')">Add SubGrupo-2</button>
                    </div>
                    <ul id="subgrupo1_{{$count}}_ul">
                        @foreach($subgrupos2 as $subgrupo2)
                            @if($subgrupo2->grupo_id == $subgrupo1->id)
                                @php
                                    $count++;
                                @endphp
                                <li style="list-style-type: none" id="subgrupo2_{{$count}}">
                                    <div class="col-md-8" style="padding: 5px;">
                                        <div class="input-group">
                                            <span class="input-group-addon">{{$subgrupo2->codigo}}</span>
                                            {!! Form::select("estrutura[0][itens][1][itens][".$count.'][id]',[''=>'Escolha...']+$selectSubgrupos2, $subgrupo2->id, ['id'=>'subgrupo2_'.$count.'_select', 'class' => 'form-control select2 estrutura_2', 'onchange'=>'percorreBloco()']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary" onclick="addSubItem('subgrupo2_{{$count}}', 2, 'estrutura[0][itens][1][itens][{{$count}}][itens]')">Add SubGrupo-3</button>
                                    </div>
                                    <ul id="subgrupo2_{{$count}}_ul">
                                        @foreach($subgrupos3 as $subgrupo3)
                                            @if($subgrupo3->grupo_id == $subgrupo2->id)
                                                @php
                                                    $count++;
                                                @endphp
                                                <li style="list-style-type: none" id="subgrupo3_{{$count}}">
                                                    <div class="col-md-8" style="padding: 5px;">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">{{$subgrupo3->codigo}}</span>
                                                            {!! Form::select("estrutura[0][itens][1][itens][2][itens][".$count.'][id]',[''=>'Escolha...']+$selectSubgrupos3, $subgrupo3->id, ['id'=>'subgrupo3_'.$count.'_select', 'class' => 'form-control select2 estrutura_3', 'onchange'=>'percorreBloco()']) !!}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn btn-primary" onclick="addSubItem('subgrupo3_{{$count}}', 3, 'estrutura[0][itens][1][itens][2][itens][{{$count}}][itens]')">Add Serviço</button>
                                                    </div>
                                                    <ul id="subgrupo3_{{$count}}_ul">
                                                        @foreach($servicos as $servico)
                                                             @if($servico->grupo_id == $subgrupo3->id)
                                                                @php
                                                                    $count++;
                                                                @endphp
                                                                <li style="list-style-type: none" id="subgrupo4_{{$count}}">
                                                                    <div class="col-md-8" style="padding: 5px;">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">{{$servico->codigo}}</span>
                                                                            {!! Form::select("estrutura[0][itens][1][itens][2][itens][3][itens][".$count.'][id]',[''=>'Escolha...']+$selectServicos, $servico->id, ['id'=>'subgrupo4_'.$count.'_select','class' => 'form-control select2 estrutura_4', 'onchange'=>'percorreBloco()']) !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <button type="button" class="btn btn-warning" onclick="RedirectAddInsumo('subgrupo4_{{$count}}', 4,'estrutura[0][itens][1][itens][2][itens][3][itens][{{$count}}]')">Adicionar Insumos</button>
                                                                    </div>
                                                                </li>
                                                             @endif
                                                             <ul id="subgrupo4_{{$count}}_ul"></ul>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            @endif
        @endforeach
        </ul>
    @else
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
    @endif
</div>



<!-- Submit Field -->
<div class="form-group col-sm-12 btn-toolbar">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save-continue') ), ['name' => 'save', 'value' => 'save-continue', 'class' => 'btn btn-success pull-right', 'type' => 'submit', 'onclick' => '']) !!}

    <a href="{!! route('admin.mascaraPadraoEstruturas.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
    <script type="text/javascript">
        var blocos = 0;
        var bloco_aberto = true;

        $(function(){
            percorreBloco();
        });

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
            var rota = "{{url('/admin/mascara-padrao-estruturas/grupos')}}/";
            nivel++;
            var labelBotao = 'Add SubGrupo-'+(nivel+1);
            if(nivel == 3){
                labelBotao = 'Add Serviço';
            }else if(nivel>3){
                rota = "{{url('/admin/mascara-padrao-estruturas/servicos')}}/";
            }
            if(nivel < 4){
                buttonAdd = '<button type="button" class="btn btn-primary" onclick="addSubItem(\'subgrupo'+nivel+'_'+blocos+'\', '+nivel+',\''+nome+'['+blocos+'][itens]'+'\')">'+labelBotao+'</button>';
            }
            if(nivel == 4){
                labelBotao = 'Insumos';
                buttonAdd = '<button type="button" class="btn btn-warning" onclick="RedirectAddInsumo(\'subgrupo'+nivel+'_'+blocos+'\', '+nivel+',\''+nome+'['+blocos+']'+'\')">'+labelBotao+'</button>';
            }

//            console.log(nivel);
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
                        '<select class="form-control select2 estrutura_'+nivel+'" onchange="percorreBloco()" name="'+nome+'['+blocos+'][id]" id="subgrupo'+nivel+'_'+blocos+'_select">' +
                        options +
                        '</select>' +
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

        function RedirectAddInsumo(nome){
            $('form').append([
                '<input type="hidden" name="'+ nome +'['+blocos+'][id]' +'">',
                '<input type="hidden" name="save" value="save-continue">',
                '<input type="hidden" name="btn_insumo" value="1">'
            ]).submit();
        }

        function percorreBloco() {
            console.log('entrei aqui');
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
