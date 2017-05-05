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
    <h3>Relacionamento de orçamentos / planejamentos</h3>
    <div class="col-md-12 thumbnail">
        <div class="col-md-12">
            <div class="caption">
                <div class="card-description">
                    <!-- Obras Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('obra_id', 'Obras:') !!}
                        {!! Form::select('obra_id', [''=>'-']+$obras, null, ['class' => 'form-control', 'id'=>'obra_id', 'required'=>'required','onchange'=>'selectPlanejamento(this.value), orcamento(this.value);']) !!}
                    </div>

                    <!-- Planejamentos de insumo Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('planejamento_id', 'Planejamentos:') !!}
                        {!! Form::select('planejamento_id', [''=>'-'], null, ['class' => 'form-control', 'id'=>'planejamento_id', 'required'=>'required']) !!}
                    </div>

                    <div id="carrinho" class="col-md-12"></div>

                    <div id="submit" class="col-md-4 col-md-offset-4">
                    </div>
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
                }).fail(function() {
                    $('.overlay').remove();
                });
            }
        }

        function orcamento(id){
            var rota = "{{url('/admin/planejamentos/planejamentoOrcamentos/orcamento')}}/";
            if(id){
                $('.box.box-primary').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
                $.ajax({
                    url: rota + id
                }).done(function(retorno) {
                    if(retorno) {
                        list = '<h3>Orçamentos</h3>' +
                                '<ul>' +
                                '<li>' +
                                '<div class="row">' +
                                '<div class="col-md-12">' +
                                '<input type="checkbox" id="insumo_' + retorno.grupo_id + '" name="grupo_id" value="' + retorno.grupo_id + '">' +
                                '<button type="button" class="btn btn-link text-left" ' +
                                'onclick="listInsumosRelacionados(' + retorno.grupo_id + ','+ retorno.obra_id +',\'subgrupo1_id\', \'grupo_id\')">' + retorno.codigo + ' - ' + retorno.nome +
                                '</button>' +
                                '<span>'+ ((retorno.tarefa) ? '- '+ retorno.tarefa : '' ) +'</span>'+
                                '<button type="button" class="btn btn-link pull-right" ' +
                                'onclick="listInsumosRelacionados(' + retorno.grupo_id + ','+ retorno.obra_id +',\'subgrupo1_id\', \'grupo_id\')">' +
                                '<i class="fa fa-plus-square pull-right" aria-hidden="true"></i>' +
                                '</button>' +
                                '<ul id="obj_' + retorno.grupo_id + '"></ul>' +
                                '</span>' +
                                '</div>' +
                                '</div>' +
                                '</li>' +
                                '</ul>';
                        $('#carrinho').html(list);

                        submit = '<button type="submit" class="btn btn-primary btn-lg">Adicionar relacionamentos</button>'
                        $('#submit').html(submit);
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
                                    '<span>'+ ((value.tarefa) ? '- ' + value.tarefa : '' ) +'</span>'+
                                    '<button type="button" class="btn btn-link pull-right" ' +
                                    'onclick="listInsumosRelacionados('+value.id+','+value.obra_id+',\''+ value.proximo+'\',\''+value.atual+'\')">'+
                                    ' <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>' +
                                    '</button>'+
                                    '</div>' +
                                    '<ul id="obj_'+value.atual+'_'+value.id+'"></ul>' +
                                    ' </div></div></li>';
                        }else{
                            list += '<li id="item_'+value.id+'"> ' +
                                    '<div class="row">' +
                                    '<div class="col-md-12">' +
                                    '<input type="checkbox" id="insumo_'+ value.insumo_id +'" name="'+value.atual+'[]" value="'+ value.insumo_id +'"> ' +value.codigo+' - '+value.nome+
                                    '<div>'+ ((value.tarefa) ? '<strong>TAREFA:</strong> ' + value.tarefa : '' ) +'</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</li>';
                        }
                    });
                    if(campo == 'grupo_id') {
                        $('#obj_' + obj_id).html(list);
                    }else{
                        $('#obj_'+campo+'_'+ obj_id).html(list);
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
    </script>
@stop