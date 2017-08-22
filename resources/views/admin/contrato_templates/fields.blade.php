<!-- Nome Field -->
<div class="form-group col-sm-12">
    {!! Form::label('nome', 'Nome:') !!}
    {!! Form::text('nome', null, ['class' => 'form-control']) !!}
</div>

<!-- Texto Field -->
<div class="form-group col-sm-12">
{!! Form::label('template', 'Template:') !!}
<!-- Info modal -->

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                       aria-expanded="false" aria-controls="collapseOne">
                        <i class="fa fa-info-circle" aria-hidden="true"></i> Tags disponíveis para o template
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                    <h5>
                        Caso desejar, pode-se criar campos para serem inseridos na hora de gerar o contrato
                        <button type="button" onclick="addCampo();" class="btn btn-xs btn-flat btn-warning"> <i class="fa fa-plus"></i> Gerar Campo</button>
                    </h5>

                    <ul class="list-group" id="campos_extras">
                        <?php
                            $campos_extras_count = 0;
                            $campos_extras = [];
                            if(isset($contratoTemplate)){
                                if( strlen(trim($contratoTemplate->campos_extras)) ){
                                    $campos_extras = json_decode($contratoTemplate->campos_extras);
                                }
                            }
                        ?>
                        @if(count($campos_extras))
                            @foreach($campos_extras as $campo_extra)
                                <?php $campos_extras_count++; ?>
                                <li id="campos_extras{{ $campos_extras_count }}" class="list-group-item">
                                    <div class="row">
                                        <span class="col-md-1 text-right">
                                            <label>Nome:</label>
                                        </span>
                                        <span class="col-md-3">
                                            <input type="hidden" name="campos_extras[{{ $campos_extras_count }}][tag]"
                                                   id="campo_extra_tag{{ $campos_extras_count }}"
                                                   required="required" value="{{ $campo_extra->tag }}">
                                            <input type="text" class="form-control" value="{{ $campo_extra->nome }}"
                                                   name="campos_extras[{{ $campos_extras_count }}][nome]"
                                                   onkeyup="slugAndShow(1, this.value);" placeholder="Nome do Campo">
                                        </span>
                                        <span class="col-md-1 text-right">
                                            <label>Tipo:</label>
                                        </span>
                                        <span class="col-md-2">
                                            <select name="campos_extras[{{ $campos_extras_count }}][tipo]"
                                                    class="form-control select2" required="required">
                                                <option {{ $campo_extra->tipo == 'texto'? 'selected="selected"':'' }}
                                                        value="texto">Texto</option>
                                                <option  {{ $campo_extra->tipo == 'numero'? 'selected="selected"':'' }}
                                                         value="numero">Número</option>
                                                <option  {{ $campo_extra->tipo == 'data'? 'selected="selected"':'' }}
                                                         value="data">Data</option>
                                            </select>
                                        </span>
                                        <span class="col-md-1 text-right">
                                            <label>Uso:</label>
                                        </span>
                                        <span class="col-md-3">
                                            <span id="campo_extra{{ $campos_extras_count }}"
                                                  class="label label-primary selecionavel">{{ $campo_extra->tag }}</span>
                                        </span>
                                        <span class="col-md-1 text-right">
                                            <button type="button" class="btn btn-danger btn-flat"
                                                    title="remover" onclick="removeTag({{ $campos_extras_count }});">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        @endif

                    </ul>

                    <h5>Para que ao gerar um contrato os dados reais sejam carregados, é necessário usar estas tags onde
                        o sistema irá substituir automaticamente.
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-building"></i>
                                    <h3 class="box-title">Obra</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [RAZAO_SOCIAL_OBRA]
                                            </span> &nbsp;
                                            Razão social
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CNPJ_OBRA]
                                            </span> &nbsp;
                                            CNPJ
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [INSCRICAO_ESTADUAL_OBRA]
                                            </span> &nbsp;
                                            Inscrição estadual
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ENDERECO_FATURAMENTO_OBRA]
                                            </span> &nbsp;
                                            Endereço de Faturamento
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ENDERECO_OBRA_OBRA]
                                            </span> &nbsp;
                                            Endereço da Obra
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ENTREGA_NOTA_FISCA_E_BOLETO_OBRA]
                                            </span> &nbsp;
                                            Entrega de Nota Fiscal e Boleto
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [AREA_TERRENO_OBRA]
                                            </span> &nbsp;
                                            Área do Terreno
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [AREA_PRIVATIVA_OBRA]
                                            </span> &nbsp;
                                            Área privativa
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [AREA_CONSTRUIDA_OBRA]
                                            </span> &nbsp;
                                            Área construída
                                        </li>

                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NUM_APARTAMENTOS_OBRA]
                                            </span> &nbsp;
                                            Número de Apartamentos
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NUM_TORRES_OBRA]
                                            </span> &nbsp;
                                            Número de torres
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NUM_PAVIMENTO_TIPO_OBRA]
                                            </span> &nbsp;
                                            Número de Pavimentos
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [DATA_INICIO_OBRA]
                                            </span> &nbsp;
                                            Data de início da Obra
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [DATA_CLIENTE_OBRA]
                                            </span> &nbsp;
                                            DATA_CLIENTE
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [INDICE_BILD_PRE_OBRA]
                                            </span> &nbsp;
                                            INDICE_BILD_PRE
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [INDICE_BILD_OI_OBRA]
                                            </span> &nbsp;
                                            INDICE_BILD_OI
                                        </li>

                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ADM_OBRA_NOME_OBRA]
                                            </span> &nbsp;
                                            Nome do Administrador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ADM_OBRA_EMAIL_OBRA]
                                            </span> &nbsp;
                                            E-Mail do Administrador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ENG_OBRA_NOME_OBRA]
                                            </span> &nbsp;
                                            Nome do Engenheiro
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ENG_OBRA_EMAIL_OBRA]
                                            </span> &nbsp;
                                            E-mail do Engenheiro
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [HORARIO_ENTREGA_NA_OBRA_OBRA]
                                            </span> &nbsp;
                                            Horário de Entregas
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [REFERENCIAS_BANCARIAS_OBRA]
                                            </span> &nbsp;
                                            Referências Bancárias
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-user-plus"></i>
                                    <h3 class="box-title">Fornecedor</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NOME_FORNECEDOR]
                                            </span> &nbsp;
                                            Razão Social
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CNPJ_FORNECEDOR]
                                            </span> &nbsp;
                                            CNPJ
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [TIPO_LOGRADOURO_FORNECEDOR]
                                            </span> &nbsp;
                                            Tipo do Logradouro
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [LOGRADOURO_FORNECEDOR]
                                            </span> &nbsp;
                                            Logradouro
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NUMERO_FORNECEDOR]
                                            </span> &nbsp;
                                            Número
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [COMPLEMENTO_FORNECEDOR]
                                            </span> &nbsp;
                                            Complemento
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [MUNICIPIO_FORNECEDOR]
                                            </span> &nbsp;
                                            Município
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ESTADO_FORNECEDOR]
                                            </span> &nbsp;
                                            Estado
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [INSCRICAO_ESTADUAL_FORNECEDOR]
                                            </span> &nbsp;
                                            Inscrição Estadual
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [EMAIL_FORNECEDOR]
                                            </span> &nbsp;
                                            E-Mail
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [SITE_FORNECEDOR]
                                            </span> &nbsp;
                                            Site
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [TELEFONE_FORNECEDOR]
                                            </span> &nbsp;
                                            Telefone
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CEP_FORNECEDOR]
                                            </span> &nbsp;
                                            CEP
                                        </li>
                                        <!--NOVOS CAMPOS-->
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NOME_SOCIO]
                                            </span> &nbsp;
                                            Nome sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NACIONALIDADE_SOCIO]
                                            </span> &nbsp;
                                            Nacionalidade sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ESTADO_CIVIL_SOCIO]
                                            </span> &nbsp;
                                            Estado civil sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [PROFISSAO_SOCIO]
                                            </span> &nbsp;
                                            Profissão sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [RG_SOCIO]
                                            </span> &nbsp;
                                            RG sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CPF_SOCIO]
                                            </span> &nbsp;
                                            CPF sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ENDERECO_SOCIO]
                                            </span> &nbsp;
                                            Endereço sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CIDADE_SOCIO]
                                            </span> &nbsp;
                                            Cidade sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [ESTADO_SOCIO]
                                            </span> &nbsp;
                                            Estado sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CEP_SOCIO]
                                            </span> &nbsp;
                                            CEP sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [TELEFONE_SOCIO]
                                            </span> &nbsp;
                                            Telefone sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [CELULAR_SOCIO]
                                            </span> &nbsp;
                                            Celular sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [EMAIL_SOCIO]
                                            </span> &nbsp;
                                            Email sócio ou procurador
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [NOME_VENDEDOR]
                                            </span> &nbsp;
                                            Nome vendedor
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [EMAIL_VENDEDOR]
                                            </span> &nbsp;
                                            Email vendedor
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [TELEFONE_VENDEDOR]
                                            </span> &nbsp;
                                            Telefone vendedor
                                        </li>
                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-file"></i>
                                    <h3 class="box-title">Contrato</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [VALOR_TOTAL_CONTRATO]
                                            </span> &nbsp;
                                            Valor Total do contrato
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [TABELA_ITENS_CONTRATO]
                                            </span> &nbsp;
                                            Tabela com a listagem de Insumo, Qtd, Valor Unitário e Valor Total
                                        </li>

                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <div class="box box-solid">
                                <div class="box-header with-border">
                                    <i class="fa fa-usd"></i>
                                    <h3 class="box-title">Proposta do Q.C.</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                               [COMPOSICAO_DO_PRECO]
                                            </span> &nbsp;
                                            Composição do preço (percentual mão-de-obra, Material Contradada, Material Fat. Direto)
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [FRETE_TIPO]
                                            </span> &nbsp;
                                            CIF ou FOB
                                        </li>
                                        <li class="list-group-item">
                                            <span class="label label-primary selecionavel">
                                                [FRETE_VALOR]
                                            </span> &nbsp;
                                            Valor do Frete
                                        </li>

                                    </ul>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    {!! Form::textarea('template', null, ['class' => 'form-control htmleditor']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ),
    ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('admin.contratoTemplates.index') !!}" class="btn btn-danger"><i
                class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}</a>
</div>
@section('scripts')
    @parent
    <script type="text/javascript">
        function slugAndShow(qual, valor){
            var valor_slug = slug(valor,'_');
            $('#campo_extra'+qual).html('['+valor_slug.toUpperCase()+']');
            $('#campo_extra_tag'+qual).val('['+valor_slug.toUpperCase()+']');
        }
        function removeTag(qual) {
            $('#campos_extras'+qual).remove();
        }
        var campos_extras_count = {{ intval($campos_extras_count) }};
        function addCampo() {
            campos_extras_count ++;
            $('#campos_extras').append('<li id="campos_extras'+campos_extras_count+'" class="list-group-item">'+
                    '<div class="row">'+
                    '<span class="col-md-1 text-right">'+
                    '<label>Nome:</label>'+
            '</span>'+
            '<span class="col-md-3">'+
                    '<input type="hidden" name="campos_extras['+campos_extras_count+'][tag]" ' +
                    'id="campo_extra_tag'+campos_extras_count+'" required="required" value="">'+
                    '<input type="text" class="form-control" name="campos_extras['+campos_extras_count+'][nome]" ' +
                    'onkeyup="slugAndShow('+campos_extras_count+', this.value);" placeholder="Nome do Campo">'+
                    '</span>'+
                    '<span class="col-md-1 text-right">'+
                    '<label>Tipo:</label>'+
            '</span>'+
            '<span class="col-md-2">'+
                    '<select name="campos_extras['+campos_extras_count+'][tipo]" class="form-control select2" ' +
                    ' required="required">'+
                    '<option value="Texto">Texto</option>'+
                    '<option value="numero">Número</option>'+
                    '<option value="data">Data</option>'+
                    '</select>'+
                    '</span>'+
                    '<span class="col-md-1 text-right">'+
                    '<label>Uso:</label>'+
            '</span>'+
            '<span class="col-md-3">'+
                    '<span id="campo_extra'+campos_extras_count+'" class="label label-primary selecionavel"></span>'+
                    '</span>'+
                    '<span class="col-md-1 text-right">'+
                    '<button type="button" class="btn btn-danger btn-flat" ' +
                    ' title="remover" onclick="removeTag('+campos_extras_count+');">'+
                    '<i class="fa fa-times"></i>'+
                    '</button>'+
                    '</span>'+
                    '</div>'+
                    '</li>');

            $("#campo_extra"+campos_extras_count).on('mouseup', function() {
                var sel, range;
                var el = $(this)[0];
                if (window.getSelection && document.createRange) { //Browser compatibility
                    sel = window.getSelection();
                    if(sel.toString() == ''){ //no text selection
                        window.setTimeout(function(){
                            range = document.createRange(); //range object
                            range.selectNodeContents(el); //sets Range
                            sel.removeAllRanges(); //remove all ranges from selection
                            sel.addRange(range);//add Range to a Selection.
                        },1);
                    }
                }else if (document.selection) { //older ie
                    sel = document.selection.createRange();
                    if(sel.text == ''){ //no text selection
                        range = document.body.createTextRange();//Creates TextRange object
                        range.moveToElementText(el);//sets Range
                        range.select(); //make selection.
                    }
                }
            });
        }
    </script>
@stop