<div class="col-md-6">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    NOTA FISCAL
                </h4>
            </div>
            <div>
                <div class="panel-body">
                    <!-- Contrato Id Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('contrato_id', 'Contrato:') !!}
                        {!! Form::select('contrato_id',[''=>'Escolha...'] + (isset($contrato) ? $contrato : []), null, ['class' => 'form-control select2']) !!}
                    </div>

                    <div class="form-group col-sm-12">
                        {!! Form::label('solicitacao_entrega_id', 'Solicitação de Entrega:') !!}
                        {!! Form::select('solicitacao_entrega_id',[''=>'Escolha...'] + (isset($solicitacoes) ? $solicitacoes : []), null, ['class' => 'form-control select2']) !!}
                    </div>

                    <!-- Codigo Field -->
                    <div class="form-group col-sm-4">
                        {!! Form::label('codigo', 'Número NFe:') !!}
                        {!! Form::text('codigo', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('serie', 'Série:') !!}
                        {!! Form::text('serie', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('tipo_entrada_saida', 'Entrada/Saída:') !!}
                        {!! Form::select('tipo_entrada_saida', \App\Models\Notafiscal::TIPOS_ENTRADA_SAIDA ,null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Natureza Operacao Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('natureza_operacao', 'Natureza Operação:') !!}
                        {!! Form::text('natureza_operacao', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Cnpj Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('cnpj', 'Cnpj:') !!}
                        {!! Form::text('cnpj', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Inscrição Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('remetente_inscricao_estadual', 'Inscrição Est:') !!}
                        {!! Form::text('remetente_inscricao_estadual', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Razao Social Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('razao_social', 'Razão Social:') !!}
                        {!! Form::text('razao_social', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Fantasia Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('fantasia', 'Fantasia:') !!}
                        {!! Form::text('fantasia', null, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Data Emissao Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('data_emissao', 'Data Emissão:') !!}
                        {!! Form::date('data_emissao', $notafiscal->data_emissao, ['class' => 'form-control']) !!}
                    </div>

                    <!-- Data Saida Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('data_saida', 'Data Saída:') !!}
                        {!! Form::date('data_saida', $notafiscal->data_saida, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="panel-body">


                    <div class="form-group col-sm-4">
                        {!! Form::label('base_calculo_icms', 'BASE DE CÁLC. DO ICMS:') !!}
                        {!! Form::text('base_calculo_icms', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_icms', 'VALOR DO ICMS:') !!}
                        {!! Form::text('valor_icms', null, ['class' => 'form-control']) !!}
                    </div>


                    <div class="form-group col-sm-4">
                        {!! Form::label('base_calculo_icms_sub', 'BASE DE CÁLC. ICMS S.T.') !!}
                        {!! Form::text('base_calculo_icms_sub', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_icms_sub', 'VALOR DO ICMS SUBST.') !!}
                        {!! Form::text('valor_icms_sub', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_imposto_importacao', 'V. IMP. IMPORTAÇÃO') !!}
                        {!! Form::text('valor_imposto_importacao', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_icms_uf_remetente', 'V. ICMS UF REMET.') !!}
                        {!! Form::text('valor_icms_uf_remetente', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_fcp', 'VALOR DO FCP') !!}
                        {!! Form::text('valor_fcp', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_pis', 'VALOR DO PIS') !!}
                        {!! Form::text('valor_pis', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_frete', 'VALOR DO FRETE') !!}
                        {!! Form::text('valor_frete', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_seguro', 'VALOR DO SEGURO') !!}
                        {!! Form::text('valor_seguro', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('desconto', 'DESCONTO') !!}
                        {!! Form::text('desconto', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('outras_despesas', 'OUTRAS DESPESAS') !!}
                        {!! Form::text('outras_despesas', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_total_ipi', 'VALOR TOTAL IPI') !!}
                        {!! Form::text('valor_total_ipi', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_icms_uf_destinatario', 'V. ICMS UF DEST.') !!}
                        {!! Form::text('valor_icms_uf_destinatario', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_total_tributos', 'V. TOT. TRIB.') !!}
                        {!! Form::text('valor_total_tributos', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_confins', 'VALOR DA COFINS') !!}
                        {!! Form::text('valor_confins', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_total_produtos', 'Valor Total produtos:') !!}
                        {!! Form::text('valor_total_produtos', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-4">
                        {!! Form::label('valor_total_nota', 'Valor Total da Nota:') !!}
                        {!! Form::text('valor_total_nota', null, ['class' => 'form-control']) !!}
                    </div>

                </div>

                <div class="panel-body">
                    <div class="form-group col-sm-6">
                        {!! Form::label('transportadora_nome', 'Transportadora:') !!}
                        {!! Form::text('transportadora_nome', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group col-sm-6">
                        {!! Form::label('tipo_entrada_saida', 'Frete por Conta:') !!}
                        {!! Form::select('tipo_entrada_saida', \App\Models\Notafiscal::FRETE_POR_CONTA_DO ,null, ['class' => 'form-control']) !!}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-6" style="min-height: 700px !important;clear:right;">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-top:10px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    Visualização
                </h4>
            </div>
            <div>
                <div class="panel-body">
                    <div class="col-md-12" style="margin-top: 10px;height: 100%;">
                        @if (strpos($notafiscal->schema, "resNFe") === FALSE)
                            <iframe type="application/pdf"
                                    src="/danfe/{{ $notafiscal->id }}"
                                    id="arquivoNfe"
                                    frameborder="0"
                                    marginheight="0"
                                    marginwidth="0"
                                    width="100%"
                                    height="100%" style="height: 830px;">
                            </iframe>
                        @else
                            Não é possível a visualização de notas resumidas.
                        @endif
                    </div>
                    <div class="form-group col-sm-12">
                        {!! Form::label('dados_adicionais', 'Dados Adicionais:') !!}
                        {!! Form::textarea('dados_adicionais', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="col-sm-12">
    <div class="col-md-12">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin-top:10px;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        ITENS DA NOTA FISCAL
                    </h4>
                </div>
                <div>
                    <div class="panel-body">
                        <table id="itens" class="table table-striped table-hover dataTable dtr-inline">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>NCM</th>
                                    <th>Código Prod</th>
                                    <th>Quantidade</th>
                                    <th>Unidade</th>
                                    <th>Valor Unit</th>
                                    <th>Valor Total</th>
                                    <th>Item Vínculado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $qtdItens = 0;
                            ?>
                            @if(isset($notafiscal))
                                @foreach($notafiscal->items as $item)
                                    <?php
                                    $qtdItens = $item->id;
                                    ?>
                                    <tr id="item_{{$qtdItens}}">
                                        <!-- idioma Id Field -->
                                        <td width="30%">
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][nome_produto]', $item->nome_produto, ['class' => 'form-control']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][ncm]', $item->ncm, ['class' => 'form-control text-right']) !!}
                                        </td>
                                        <td >
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][codigo_produto]', $item->codigo_produto, ['class' => 'form-control text-right']) !!}
                                        </td>
                                        <td >
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][qtd]', $item->qtd, ['class' => 'form-control text-right']) !!}
                                        </td>
                                        <td >
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][unidade]', $item->unidade, ['class' => 'form-control text-right']) !!}
                                        </td>
                                        <td >
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][valor_unitario]', $item->valor_unitario, ['class' => 'form-control text-right']) !!}
                                        </td>
                                        <td >
                                            {!! Form::text('notaFiscalItens['.$qtdItens.'][valor_total]', $item->valor_total, ['class' => 'form-control text-right']) !!}
                                            {!! Form::hidden('notaFiscalItens['.$qtdItens.'][id]',$item->id) !!}
                                            {!! Form::hidden('notaFiscalItens['.$qtdItens.'][tipo_equalizacao_tecnica_id]',$item->tipo_equalizacao_tecnica_id) !!}
                                        </td>
                                        <td >
                                            <a class="btn btn-success">Vincular</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12" style="margin-top: 20px;">
    {!! Form::button( '<i class="fa fa-remove"></i> Rejeitar', ['class' => 'btn btn-danger pull-right', 'type'=>'submit']) !!}

    {!! Form::button( '<i class="fa fa-save"></i> Aceite', ['class' => 'btn btn-success pull-right', 'type'=>'submit']) !!}


    <a href="{!! route('notafiscals.index') !!}" class="btn btn-default">
        <i class="fa fa-times"></i>
        {{ ucfirst( trans('common.cancel') )}}
    </a>
</div>

@section('scripts')
    <script type="text/javascript">
        var qtditens = {{ isset($notafiscal)? $qtdItens:'0' }};

        function addItens() {
            qtditens++;
            $('#itens').append(
                    '<div id="item_' + qtditens + '" class="panel panel-default">' +
                    '<div class="panel-heading" role="tab" id="heading_' + qtditens + '">' +
                    '<h4 class="panel-title">' +
                    '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_' + qtditens + '" aria-expanded="false" aria-controls="collapse_' + qtditens + '">' +
                    "Item: " + qtditens + '<span type="button" onclick="remExtra(' + qtditens + ',\'item_\')" class="btn btn-danger btn-xs pull-right" title="Remover"><i class="fa fa-times"></i></span>' +
                    '</a>' +
                    '</h4>' +
                    '</div>' +
                    '<div id="collapse_' + qtditens + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_' + qtditens + '">' +
                    '<div class="panel-body">' +
                    "Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS." +
                    '</div>' +
                    '</div>' +
                    '</div>'
//                    '<div id="item_'+qtditens+'">' +
//                    '<div class="form-group col-sm-11">'+
//                    '<label for="itens['+qtditens+'][nome]">Nome:</label>'+
//                    '<input class="form-control" name="itens[' + qtditens + '][nome]" type="text" id="itens['+qtditens+'][nome]" required="required" />'+
//                    '</div>'+
//                    '<div class="form-group col-sm-1"><button type="button" onclick="remExtra('+qtditens+',\'item_\')" class="btn btn-danger" style="margin-top: 24px" title="Remover"><i class="fa fa-times"></i></button> </div>'+
//                    '</div>'
            );
        }

        function readURL(input) {
            startLoading();
            if (input.files && input.files[0]) {
                var view = new FileReader();
                view.onload = function (e) {
//                    window.open(e.target.result);
                    $('#arquivoNfe')
                            .attr('src', e.target.result)
                            .width(620)
                            .height(700);
                };
                view.readAsDataURL(input.files[0]);
            }
            stopLoading();
            $('#arquivo_nfe').val($('#arquivoNfe').val());
        }

        function remExtra(qual, nome) {
            $('#' + nome + '' + qual).remove();
        }
    </script>
@stop
