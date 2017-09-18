
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
    <h3>Relacionamento de orçamentos / tarefas</h3>
    <div class="col-md-12 thumbnail">
        <div class="col-md-12">
            <div class="caption">
                <div class="card-description">
                    <!-- Obras Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('obra_id', 'Obras:') !!}
                        {!! Form::select('obra_id', [''=>'-']+$obras, Request::get('obra_id'), ['class' => 'form-control', 'id'=>'obra_id', 'required'=>'required','onchange'=>'selectPlanejamento(this.value), orcamento(this.value), selectGrupoInsumo();']) !!}
                    </div>

                    <!-- Planejamentos de insumo Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('planejamento_id', 'Tarefa:') !!}
                        {!! Form::select('planejamento_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'planejamento_id', 'required'=>'required']) !!}
                    </div>

                    <!-- Grupo de insumos Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('grupo_insumo_id', 'Grupo de insumos:') !!}
                        {!! Form::select('grupo_insumo_id', [''=>'-'], null, ['class' => 'form-control select2', 'id'=>'grupo_insumo_id', 'onchange'=>'grupoInsumos(this.value)']) !!}
                    </div>
                    <div class="submit col-md-4 col-md-offset-4"></div>
                    <div id="carrinho" class="col-md-12"></div>
                    <div id="grupo_insumos" class="col-md-12"></div>

                    <div class="submit col-md-4 col-md-offset-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        function selectPlanejamento(id){
            var rota = "{{url('/admin/planejamentos/planejamentoOrcamentos/planejamento')}}/";
            if(id){

                $("#js-btn-semPlanejamento").removeClass('hide');
                $("#js-btn-semPlanejamento").attr("href", "{{url('/admin/planejamentos/planejamentoOrcamentos/sem-planejamento/view')}}/"+id)

                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    options = '<option value="">Selecione</option>';
                    $('#planejamento_id').html(options);
                    $.each(retorno,function(index, value){
                        options += '<option value="'+index+'">'+value+'</option>';
                    });
                    $('#planejamento_id').html(options);
                    $('.overlay').remove();
                    $('#planejamento_id').attr('disabled',false);
                    $('#planejamento_id').trigger('change');
                }).fail(function() {
                    $('.overlay').remove();
                });

            } else {

                $("#js-btn-semPlanejamento").addClass('hide');
            }
        }

        @if(Request::get('obra_id'))
            $(function(){
            selectPlanejamento({{Request::get('obra_id')}});
            orcamento({{Request::get('obra_id')}});
            selectGrupoInsumo();
            });
        @endif

        function selectGrupoInsumo(){
            var rota = "{{url('/admin/planejamentos/planejamentoOrcamentos/planejamento/orcamento/insumo_grupos')}}";
            $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            $.ajax({
                url: rota
            }).done(function(retorno) {
                options = '<option value="">Selecione</option>';
                $('#grupo_insumo_id').html(options);
                $.each(retorno,function(index, value){
                    options += '<option value="'+index+'">'+value+'</option>';
                });
                $('#grupo_insumo_id').html(options);
                $('.overlay').remove();
                $('#grupo_insumo_id').attr('disabled',false);
                $('#grupo_insumo_id').trigger('change');
            }).fail(function() {
                $('.overlay').remove();
            });
        }

        function orcamento(id){
            var rota = "{{url('/admin/planejamentos/planejamentoOrcamentos/orcamento')}}/";
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    list = '';
                    if(retorno) {
                        list = '<h3>Orçamento</h3>' +
                                '<ul>' +
                                '<li>' +
                                '<div class="row">' +
                                '<div class="col-md-12">' +
                                '<input type="checkbox" id="insumo_' + retorno.grupo_id + '" name="grupo_id" value="' + retorno.grupo_id + '">' +
                                '<button type="button" class="btn btn-link text-left" ' +
                                'onclick="listInsumosRelacionados(' + retorno.grupo_id + ','+ retorno.obra_id +',\'subgrupo1_id\', \'grupo_id\')">' + retorno.codigo + ' - ' + retorno.nome +
                                '</button>' +
                                '<span>'+ ((retorno.tarefa) ? ' - '+ retorno.tarefa : '' ) +'</span>'+
                                '<button type="button" class="btn btn-link pull-right" ' +
                                'onclick="listInsumosRelacionados(' + retorno.grupo_id + ','+ retorno.obra_id +',\'subgrupo1_id\', \'grupo_id\')">' +
                                '<i class="fa fa-plus-square pull-right"  id="ico_' + retorno.grupo_id + '" aria-hidden="true"></i>' +
                                '</button>' +
                                '<ul id="obj_' + retorno.grupo_id + '" expandido="0"></ul>' +
                                '</span>' +
                                '</div>' +
                                '</div>' +
                                '</li>' +
                                '</ul>';
                        $('#carrinho').html(list);

                        submit = '<button type="submit" class="btn btn-primary btn-lg btn-flat"><i class="fa fa-link" aria-hidden="true"></i> Relacionar Selecionados</button>';
                        $('.submit').html(submit);
                    }else{
                        list = 'Essa obras não tem orçamentos';
                        $('#carrinho').html(list);
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
            }
        }

        function listInsumosRelacionados(obj_id, obra_id, tipo, campo){
            if(campo == 'grupo_id') {
                if($('#obj_' + obj_id).attr('expandido')=='1'){
                    $('#obj_' + obj_id).attr('expandido','0');
                    $('#ico_' + obj_id).addClass('fa-plus-square');
                    $('#ico_' + obj_id).removeClass('fa-minus-square');
                    $('#obj_' + obj_id).html('');
                    return false;
                }else{

                    $('#ico_' + obj_id).addClass('fa-minus-square');
                    $('#ico_' + obj_id).removeClass('fa-plus-square');
                }
            }else{
                if($('#obj_'+campo+'_'+ obj_id).attr('expandido')=='1'){
                    $('#obj_'+campo+'_'+ obj_id).attr('expandido','0');
                    $('#ico_' + campo+'_'+ obj_id).addClass('fa-plus-square');
                    $('#ico_' + campo+'_'+ obj_id).removeClass('fa-minus-square');
                    $('#obj_'+campo+'_'+ obj_id).html('');
                    return false;
                }else{

                    $('#ico_' + campo+'_'+ obj_id).addClass('fa-minus-square');
                    $('#ico_' + campo+'_'+ obj_id).removeClass('fa-plus-square');
                }
            }

            $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
            if(obj_id && planejamento_id){
                $.ajax("{{ url('/admin/planejamentos/planejamentoOrcamentos/orcamentos/relacionados') }}", {
                            data: {
                                'id' : obj_id,
                                'obra' : obra_id,
                                'campo': campo,
                                'tipo' : tipo
                            },
                            type: "GET"
                        }
                ).done(function(retorno) {
                    list = '';
                    $.each(retorno,function(index, value){
                        if(value.atual!='insumo_id'){
                            list += '<li> <div class="row"><div class="col-md-12">' +
                                    '<div class="col-md-12">' +
                                    '<input type="checkbox" id="insumo_'+ value.id +'" name="'+value.atual+'[]" value="'+ value.id +'"> ' +
                                    '<button type="button" class="btn btn-link text-left" ' +
                                    'onclick="listInsumosRelacionados('+value.id+','+value.obra_id+',\''+ value.proximo+'\',\''+value.atual+'\')">'+value.codigo+' - '+value.nome+
                                    '</button>'+
                                    ( value.tarefa ? ' <span id="vinc_'+value.atual+'_'+ value.id +'"><span class="label label-info"><i class="fa fa-link"></i> TAREFA: ' + value.tarefa +'</span> &nbsp;' +
                                    '<button type="button" class="btn btn-danger btn-xs btn-flat" title="Desvincular" onclick="desvincular(\''+value.atual+'\','+value.id+','+value.obra_id+')">' +
                                    '<i class="fa fa-chain-broken"></i> </button> </span>': '' ) +
                                    '<button type="button" class="btn btn-link pull-right" ' +
                                    'onclick="listInsumosRelacionados('+value.id+','+value.obra_id+',\''+ value.proximo+'\',\''+value.atual+'\')">'+
                                    ' <i class="fa fa-plus-square pull-right" id="ico_'+value.atual+'_'+value.id+'" aria-hidden="true"></i>' +
                                    '</button>'+
                                    '</div>' +
                                    '<ul id="obj_'+value.atual+'_'+value.id+'" expandido="0"></ul>' +
                                    ' </div></div></li>';
                        }else{
                            list += '<li id="item_'+value.id+'"> ' +
                                    '<div class="row">' +
                                    '<div class="col-md-12">' +
                                    '<input type="checkbox" id="insumo_'+ value.insumo_id +'" name="'+value.atual+'[]" value="'+ value.insumo_id +'"> ' +value.codigo+' - '+value.nome+
                                    (value.tarefa ? ' <span id="vinc_'+value.atual+'_'+ value.id +'"><span class="label label-info"><i class="fa fa-link"></i> TAREFA: '+  value.tarefa +'</span> &nbsp;' +
                                    '<button type="button" class="btn btn-danger btn-xs btn-flat" title="Desvincular" onclick="desvincular(\''+value.atual+'\','+value.id+','+value.obra_id+')">' +
                                    '<i class="fa fa-chain-broken"></i> </button></span> ': '' ) +
                                    '</div>' +
                                    '</div>' +
                                    '</li>';
                        }
                    });
                    if(campo == 'grupo_id') {
                        $('#obj_' + obj_id).html(list);
                        $('#obj_' + obj_id).attr('expandido','1');
                    }else{
                        $('#obj_'+campo+'_'+ obj_id).html(list);
                        $('#obj_'+campo+'_'+ obj_id).attr('expandido','1');
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
            }
        }

        function desvincular(oque,quem,obra_id){
            swal({
                        title: "Tem certeza que quer desvincular"+(quem!='insumo_id'?' tudo abaixo':' este item de orçamento')+"?",
                        text: "",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, Remova o relacionamento.",
                        cancelButtonText: "Não",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $.ajax('/admin/planejamentos/planejamentoOrcamentos/orcamentos/desvincular?campo='+oque+'&id='+quem+'&obra_id='+obra_id)
                                    .done(function(retorno){
                                        swal("Desvinculado!", "", "success");
                                        $('#vinc_'+oque+'_'+quem).remove();
                                        $('#obj_'+oque+'_'+ quem).attr('expandido','0');
                                        $('#ico_' + oque+'_'+ quem).addClass('fa-plus-square');
                                        $('#ico_' + oque+'_'+ quem).removeClass('fa-minus-square');
                                        $('#obj_'+oque+'_'+ quem).html('');
                                    })
                                    .fail(function (retorno) {
                                        swal("Erro ao desvincular!", "", "error");
                                    });

                        }
                    });
        }

        function grupoInsumos(id){
            var obra_id = $('#obra_id').val();
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax("{{ url('/admin/planejamentos/planejamentoOrcamentos/planejamento/orcamento/insumo/insumo_grupos') }}", {
                            data: {
                                'id' : id,
                                'obra_id' : obra_id
                            },
                            type: "GET"
                        }
                ).done(function(retorno) {
                    console.log(retorno);
                    $('#carrinho').css('display','none');
                    $('#grupo_insumos').css('display','');
                    list = '';
                    if(retorno.length > 0) {
                        list += '<h3>Insumos</h3><li><input type="checkbox" id="checkAll"> <label>Selecionar todos</label></li>\
                                </div>';
                        $.each(retorno,function(index, value){
                            list += '<li><input type="checkbox" class="grupos_insumos" id="insumo_'+ value.id +
                                    '" name="insumo_id[]" value="'+ value.id +'"> <label for="insumo_'+ value.id +'">' +
                                    value.codigo_insumo + ' - ' + value.nome +
                                    '</label>'+
                                    (value.tarefa ? ' <span class="label label-info"> <i class="fa fa-link"></i> TAREFA: ' +value.tarefa+'</span>' : '') +
                            '</li>'+
                            '    </div>';
                        });

                        $('#grupo_insumos').html('<ul style="list-style: none">' +list+ '</ul>');

                        submit = '<button type="submit" class="btn btn-primary btn-lg btn-flat"><i class="fa fa-link"></i> Relacionar Selecionados</button>';
                        $('#submit').html(submit);

                        $('#checkAll').on('ifChanged', function (event) {
                            $(".grupos_insumos").prop('checked', $(event.target).prop("checked"));
                            $("input").iCheck('update');
                        });
                    }else{
                        list = 'O grupo não tem insumos orçados';
                        $('#grupo_insumos').html('<ul style="list-style: none">' +list+ '</ul>');
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
            }
            else{
                $('#carrinho').css('display','');
                $('#grupo_insumos').css('display','none');
            }
        }
    </script>
@stop