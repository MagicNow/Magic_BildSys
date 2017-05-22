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
    <a href="{!! route('admin.contratoTemplates.index') !!}" class="btn btn-default"><i
                class="fa fa-times"></i> {{ ucfirst( trans('common.cancel') )}}</a>
</div>