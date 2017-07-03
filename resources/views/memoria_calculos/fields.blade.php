<style type="text/css">
    .fa-th-large{
        cursor: ns-resize;
    }
    .table-bordered td{
        border: 1px solid #5a5555 !important;
    }
</style>

<!-- Nome Field -->
<div class="form-group col-sm-4">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control','required'=>'required']) !!}
</div>

<!-- Modo Field -->
<div class="form-group col-sm-4">
    {!! Form::label('modo', 'Modo:') !!}
    {!! Form::select('modo',['T'=>'Torre','C'=>'Cartela','U'=>'Unidade'], null,
        ['class' => 'form-control select2', 'required'=>'required', 'onChange'=>'mudaModo(this.value);']) !!}
</div>

<!-- Padrão Field -->
<div class="form-group col-sm-2">
    {!! Form::label('padrao', 'Padrão:') !!}
    <div class="form-control">
        {!! Form::checkbox('padrao',1, null,['id'=>'padrao']) !!}
    </div>

</div>
<div class="form-group col-sm-2">
    {!! Form::label('bloco', 'Blocos:') !!}
    <div >
        <button type="button" id="btn_adicionar_bloco" onclick="adicionaBloco();"
                class="btn btn-flat btn-primary btn-block" disabled="disabled">
            <i class="fa fa-plus"></i> Adicionar
        </button>
    </div>
</div>
<div>
    <div class="col-sm-12">
        <ul class="list-group" id="blocos">


        </ul>
    </div>
</div>

<div class="col-sm-12" id="visual">

</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('memoriaCalculos.index') !!}" class="btn btn-default"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>
@section('scripts')
<script type="text/javascript">
    $(function () {
       // ready
        @if(isset($memoriaCalculo))
            buscaNomeclaturas('{{ $memoriaCalculo->modo }}');
        @else
            buscaNomeclaturas('T');
        @endif

        sortable('#blocos', {
            handle: 'i'
        });

        sortable('#blocos')[0].addEventListener('sortstop', function(e) {
            atualizaVisual();
        });

    });
    var estruturasObjs = [];
    var pavimentosObjs = [];
    var trechosObjs = [];

    var estruturas = '';
    var pavimentos = '';
    var trechos = '';
    var nomeEstrutura = '';
    var nomePavimento = '';
    var nomeTrecho = '';
    var estruturaArray = [];

    function mudaModo(valor) {
        $('#blocos').html('');
        buscaNomeclaturas(valor);
    }
    function trocaNomeEstrutura(bloco) {
        nome = $("#estrutura_bloco_"+bloco+" option:selected").text();
        estruturaArray[bloco].nome = nome;
        estruturaArray[bloco].objId = $("#estrutura_bloco_"+bloco+" option:selected").val();
        
        atualizaVisual();
    }

    function trocaNomePavimento(bloco, pavimento) {
        nome = $("#pavimentos_"+bloco+"_"+pavimento+" option:selected").text();
        estruturaArray[bloco].itens[pavimento].nome = nome;
        estruturaArray[bloco].itens[pavimento].objId = $("#pavimentos_"+bloco+"_"+pavimento+" option:selected").val();

        atualizaVisual();
    }

    function trocaNomeTrecho(bloco, pavimento, trecho) {
        nome = $("#trecho_"+trecho+" option:selected").text();
        estruturaArray[bloco].itens[pavimento].itens[trecho].nome = nome;
        estruturaArray[bloco].itens[pavimento].itens[trecho].objId = $("#trecho_"+trecho+" option:selected").val();

        atualizaVisual();
    }
    
    function atualizaVisual() {
        arrayVisual = [];

        estruturaArray.forEach(function (item, index) {
            objeto = $('#bloco_list_item_'+item.id);
            idx = $(objeto).parent().children().index(objeto);
            item.ordem = idx;
            $('#estrutura_bloco_ordem_'+item.id).val(idx);
            // Ordena os itens
            arrayPavimentosOrdenada = [];
            if(item.itens.length > 0){
                item.itens.forEach(function (linhaPav, indicePav){
                    objetoPav = $('#linha_'+item.id+'_'+linhaPav.id);
                    idxPav = $(objetoPav).parent().children().index(objeto);
                    linhaPav.ordem = idxPav;
                    $('#pavimento_bloco_ordem_'+item.id+'_'+linhaPav.id).val(idxPav);

                    arrayPavimentosOrdenada[idxPav] = linhaPav;
                });
            }
            item.itens = arrayPavimentosOrdenada;
            arrayVisual[idx] = item;
        });

        // -- Preenche o visual
        visualHTML = '';
        arrayVisual.forEach(function (item, index) {
            objDBEstrutura = estruturasObjs[item.objId];
            largura = 100;
            if(objDBEstrutura != undefined){
                largura = objDBEstrutura.largura_visual;
            }
            pavimentosDestaEstrutura = '';
            if(item.itens.length==0){
                pavimentosDestaEstrutura = '   <tr>' +
                        '       <td colspan="2"> &nbsp; </td>' +
                        '   </tr>';
            }else{
                item.itens.forEach(function (linhaPavimento, indicePav) {
                    objDBEstruturaPav = estruturasObjs[linhaPavimento.objId];
                    larguraPav = 100;
                    if(objDBEstruturaPav != undefined){
                        larguraPav = objDBEstruturaPav.largura_visual;
                    }
                    trechosDestePavimento = '';
                    if(linhaPavimento.itens.length==0){
                        trechosDestePavimento = '<table  style="width: '+larguraPav + '%; margin:0px auto; min-height: 31px;"><tr> <td> &nbsp;</td> </tr></table>';
                    }else{
                        trechosTD = '';
                        linhaPavimento.itens.forEach(function (trechoPav, indiceTrec) {
                            objDBEstruturaTrecho = estruturasObjs[trechoPav.objId];
                            larguraTrecho = 100;
                            if (objDBEstruturaTrecho != undefined) {
                                larguraTrecho = objDBEstruturaTrecho.largura_visual;
                            }
                            trechosTD += '<td>&nbsp;'+trechoPav.nome+'&nbsp;</td>';
                        });
                        trechosDestePavimento = '<table  style="width: '+larguraPav + '%; margin:0px auto;min-height: 31px;"><tr> '+trechosTD+' </tr></table>';
                    }

                    pavimentosDestaEstrutura += '   <tr><td class="warning" width="10%">'+linhaPavimento.nome+'</td> ' +
                            ' <td style="padding: 0px !important;"> '+trechosDestePavimento+'</td> ' +
                            ' </tr>';
                });
            }

            visualHTML += '<div>' +
                    '<div class="col-sm-1">'+ item.nome+ '</div> ' +
                    '<div class="col-sm-11"> ' +
                    ' <table class="table table-bordered table-condensed" style="width: '+largura + '%; margin:5px auto;"> ' +
                             pavimentosDestaEstrutura+
                    ' </table> ' +
                    '</div> ' +
                    '</div>';
        });
        $('#visual').html(visualHTML);

    }

    blocos = 0;
    function removeBloco(qual){
        $('#bloco_list_item_'+qual).remove();
        delete estruturaArray[qual];
        atualizaVisual();
    }

    function removeLinha(bloco, pavimento){
        $('#linha_'+bloco+'_'+pavimento).remove();
        delete estruturaArray[bloco].itens[pavimento];
        atualizaVisual();
    }

    function removeTrecho(bloco, pavimento, trecho){
        $('#blocoTrecho_'+trecho).remove();
        delete estruturaArray[bloco].itens[pavimento].itens[trecho];
        atualizaVisual();
    }
    function adicionaBloco() {
        blocos++;
        estruturaArray[blocos] = {
            id:blocos,
            nome:'',
            objId: null,
            itens:[],
            ordem: null
        };
        blocoHTML = '' +
        '<li class="list-group-item" id="bloco_list_item_'+blocos+'">' +
            '<div class="row" style="margin-bottom: 10px">' +
                '<div class="col-sm-9">' +
                    '<i class="fa fa-th-large"></i> &nbsp; ' +
                    nomeEstrutura+':' +
                    '<select class="form-control select2" id="estrutura_bloco_'+blocos+'" ' +
                    ' onchange="trocaNomeEstrutura('+blocos+')" name="estrutura_bloco['+blocos+']">' +
                        estruturas +
                    '</select><input type="hidden" name="estrutura_bloco_ordem['+blocos+']" id="estrutura_bloco_ordem_'+blocos+'" value="">' +
                '</div>' +
                '<div class="col-sm-3" style="min-height: 54px; padding-top: 20px">' +
                    '<button type="button" onclick="adicionaPavimento('+blocos+')" class="btn btn-flat btn-info">' +
                        '<i class="fa fa-plus"></i> Adicionar '+ nomePavimento +
                    '</button>' +
                    '<button type="button" onclick="removeBloco('+blocos+')" title="Remover" class="btn btn-flat btn-danger">' +
                        '<i class="fa fa-times"></i> '+
                    '</button>' +
                '</div>' +
            '</div>' +
            '<div style="clear: both">' +
                    '<ul class="list-group" id="pavimentos_'+blocos+'">' +
                    '</ul>' +
            '</div>' +
        '</li>';
        $('#blocos').prepend(blocoHTML);
        $('#estrutura_bloco_'+blocos).select2({
            theme: 'bootstrap',
            placeholder: "-",
            language: "pt-BR",
            allowClear: true
        });

        sortable('#blocos', 'reload');
        atualizaVisual();
    }

    pavimentosCount = 0;
    function adicionaPavimento(bloco) {
        pavimentosCount++;
        estruturaArray[bloco].itens[pavimentosCount] = {
            id:pavimentosCount,
            nome:'',
            objId: null,
            itens:[],
            ordem: null
        };
        pavimentoHTML = '' +
                '<li class="list-group-item" id="linha_'+bloco+'_'+pavimentosCount+'">' +
                '<div class="row" style="margin-bottom: 10px">' +
                '<div class="col-sm-9">' +
                '<i class="fa fa-th-large"></i> &nbsp; ' +
                nomePavimento+':' +
                '<select class="form-control select2" id="pavimentos_'+bloco+'_'+pavimentosCount+'" ' +
                ' onchange="trocaNomePavimento('+bloco+','+pavimentosCount+')" name="pavimentos['+bloco+']['+pavimentosCount+']">' +
                pavimentos +
                '</select> ' +
                ' <input type="hidden" name="pavimento_bloco_ordem['+bloco+']['+pavimentosCount+']" id="pavimento_bloco_ordem_'+bloco+'_'+pavimentosCount+'" value="">' +
                '</div>' +
                '<div class="col-sm-3" style="min-height: 54px; padding-top: 20px">' +
                '<button type="button" onclick="adicionaTrecho('+bloco+','+pavimentosCount+')" class="btn btn-flat btn-warning">' +
                '<i class="fa fa-plus"></i> Adicionar '+ nomeTrecho +
                '</button>' +
                '<button type="button" onclick="removeLinha('+blocos+','+pavimentosCount+')" title="Remover" class="btn btn-flat btn-danger">' +
                '<i class="fa fa-times"></i> '+
                '</button>' +
                '</div>' +
                '</div>' +
                '<div style="clear: both">' +
                '<ul class="list-group" id="trechos_'+bloco+'_'+pavimentosCount+'">' +
                '</ul>' +
                '</div>' +
                '</li>';
        $('#pavimentos_'+bloco).append(pavimentoHTML);
        $('#pavimentos_'+bloco+'_'+pavimentosCount).select2({
            theme: 'bootstrap',
            placeholder: "-",
            language: "pt-BR",
            allowClear: true
        });
        sortable('#pavimentos_'+bloco,'reload');

    }

    trechosCount = 0;
    function adicionaTrecho(bloco, pavimento) {
        trechosCount++;
        estruturaArray[bloco].itens[pavimento].itens[trechosCount] = {
            id:trechosCount,
            nome:'',
            objId: null,
            ordem: null
        };
        trechoHTML = '' +
        '<li class="list-group-item" id="blocoTrecho_'+trechosCount+'">' +
                '<div class="input-group">' +
                    '<span class="input-group-addon" id="trecho'+trechosCount+'">'+nomeTrecho+'</span>' +
                    '<select class="form-control select2" onchange="trocaNomeTrecho('+bloco+','+pavimento+','+trechosCount+')"  ' +
                    ' name="trecho['+bloco+']['+pavimento+']['+trechosCount+']" id="trecho_'+trechosCount+'">' +
                        trechos +
                    '</select>' +
                    ' <input type="hidden" name="trecho_bloco_ordem['+bloco+']['+pavimento+']['+trechosCount+']" ' +
                    ' id="trecho_bloco_ordem_'+bloco+'_'+pavimento+'_'+trechosCount+'" value="">' +
                    '<span class="input-group-btn">'+
                    '<button type="button" onclick="removeTrecho('+blocos+','+pavimento+','+trechosCount+')" title="Remover" class="btn btn-flat btn-danger">' +
                    '<i class="fa fa-times"></i> '+
                    '</button>' +'</span>' +
                '</div>' +
        '</li>';
        $('#trechos_'+bloco+'_'+pavimento).append(trechoHTML);
        $('#trecho_'+trechosCount).select2({
            theme: 'bootstrap',
            placeholder: "-",
            language: "pt-BR",
            allowClear: true
        });
        sortable('#trechos_'+bloco+'_'+pavimento,'reload');
    }



    function buscaNomeclaturas(valor) {
        if(valor=='T'){
            nomeEstrutura = 'Estrutura';
            nomePavimento = 'Pavimento';
            nomeTrecho = 'Trecho';
        }else{
            nomeEstrutura = 'Bloco';
            nomePavimento = 'Linha';
            nomeTrecho = 'Coluna';
        }
        estruturasObjs = [];
        pavimentosObjs = [];
        trechosObjs = [];

        $('#btn_adicionar_bloco').attr('disabled',true);
        estruturas = '<option value="" selected="selected">Escolha</option>';
        pavimentos = '<option value="" selected="selected">Escolha</option>';
        trechos = '<option value="" selected="selected">Escolha</option>';
        $.ajax('/nomeclatura-mapas/json?modo='+valor)
                .fail(function (retorno) {
                    swal({title:'Erro na solicitação',type:'error'}, function () {
                        document.location.reload();
                    });
                })
                .done(function (retorno) {
                    $.each(retorno,function (index, nomeclatura) {
                       if(nomeclatura.tipo == 1){
                           estruturas += '<option value="'+nomeclatura.id+'">'+nomeclatura.nome+'</option>';
                           estruturasObjs[nomeclatura.id] = nomeclatura;
                       }
                        if(nomeclatura.tipo == 2){
                            pavimentos += '<option value="'+nomeclatura.id+'">'+nomeclatura.nome+'</option>';
                            pavimentosObjs[nomeclatura.id] = nomeclatura;
                        }
                        if(nomeclatura.tipo == 3){
                            trechos+= '<option value="'+nomeclatura.id+'">'+nomeclatura.nome+'</option>';
                            trechosObjs[nomeclatura.id] = nomeclatura;
                        }
                    });
                    $('#btn_adicionar_bloco').attr('disabled',false);
                });
    }
</script>
@stop