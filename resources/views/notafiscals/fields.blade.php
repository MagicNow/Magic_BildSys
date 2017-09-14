@if(!empty($notafiscal->status))
    <div class="col-md-12">
        <span style="font-size:16px" class="pull-right">
        Status: {!! $notafiscal->status == 'Aceita' ? '<span class="label label-success">Aceita</span>' :  '<span class="label label-error">Rejeitada</span>' !!}
        - Data: <span class="label label-default">{!! $notafiscal->status_data->format("d/m/Y H:i:s") !!}</span>
        - Usuário: <span class="label label-default">{!! $notafiscal->statusUser ? $notafiscal->statusUser->name : "" !!}</span>
        </span>
    </div>
    <br/>
    <br/>
@endif

<div class="col-md-6">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    NOTA FISCAL
                </h4>
            </div>
            <div>
                <?php
                $contrato_id = request('contrato', $notafiscal ? $notafiscal->contrato_id : null);
                ?>
                <div class="panel-body">
                    <!-- Contrato Id Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::label('contrato_id', 'Contrato:') !!}
                        {!! Form::select('contrato_id',[
                        ''=>'Escolha...'
                        ] + (isset($contratos) ? $contratos : []),
                        $contrato_id, ['class' => 'form-control select2']) !!}
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
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" >
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
                                <th></th>
                            </tr>
                            </thead>
                            <tbody v-for="(item, $index) in itemsNf">

                            <tr :id="'item-' + item.id">
                                <!-- idioma Id Field -->
                                <td width="30%">
                                    {!! Form::text('items[nome_produto][]', null, [
                                    'class' => 'form-control',
                                    ':value' => 'item.nome_produto',
                                        'readonly'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('items[ncm][]', null, [
                                    'class' => 'form-control text-right',
                                    ':value' => 'item.ncm',
                                        'readonly'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('items[codigo_produto][]', null, [
                                    'class' => 'form-control text-right',
                                    ':value' => 'item.codigo_produto',
                                        'readonly'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('items[qtd][]', null, [
                                    'class' => 'form-control text-right',
                                    ':value' => 'item.qtd',
                                        'readonly'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('items[unidade][]', null, [
                                    'class' => 'form-control text-right',
                                    ':value' => 'item.unidade',
                                        'readonly'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('items[valor_unitario][]', null, [
                                    'class' => 'form-control text-right',
                                    ':value' => 'item.valor_unitario',
                                        'readonly'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('items[valor_total][]', null, [
                                        'class' => 'form-control text-right',
                                        ':value' => 'item.valor_total',
                                        'readonly'
                                    ]) !!}

                                    {!! Form::hidden('items[id][]', null, [':value' => 'item.id']) !!}

                                    {!! Form::hidden('items[solicitacao_entrega_itens_id][]', null,
                                        [':value' => 'item.solicitacao_entrega_itens_id']) !!}
                                </td>
                                <td>
                                    <a v-show="item.solicitacao_entrega_itens_id == null" class="btn btn-success"
                                       v-on:click="openModal($index)">
                                        Vincular
                                    </a>

                                    <a v-show="item.solicitacao_entrega_itens_id != null" class="btn btn-danger"
                                       v-on:click="desvincular($index)">
                                        Desvincular
                                    </a>
                                </td>
                            </tr>

                            <tr v-show="item.solicitacao_entrega_itens_id > 0">
                                <td colspan="8" style="background-color: rgb(170, 235, 255);text-align: left;">
                                    <div v-if="item.solicitacao_entrega_item != null"
                                         v-show="item.solicitacao_entrega_item.id > 0">
                                        Vinculado à: @{{ item.solicitacao_entrega_item.insumo.nome }}
                                        -
                                        Qtd: @{{ item.solicitacao_entrega_item.qtd }} @{{ item.solicitacao_entrega_item.unidade_sigla}}
                                        - Vl. Unit.: @{{ item.solicitacao_entrega_item.valor_unitario }}
                                        - Vl. Tot.: @{{ item.solicitacao_entrega_item.valor_total }}
                                    </div>
                                    <div v-show="item.solicitacao_entrega_itens_text != null">
                                        Vinculado à: @{{ item.solicitacao_entrega_itens_text }}
                                    </div>
                                <td>
                            </tr>

                            </tbody>
                        </table>
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
                    <div class="panel-title">
                        Faturas da Nota Fiscal

                        <button type="button"
                                v-on:click="adicionarFatura"
                                class="btn btn-sm btn-primary pull-right">
                            Adicionar Fatura
                        </button>
                        <br/>
                    </div>
                </div>
                <div class="panel-body">

                    <div class="col-md-3 box-rounded-bordered"
                         v-for="(fatura, $index) in faturasNf"
                         :id="'fatura-' + fatura.id">
                        <button type="button"
                                v-on:click="removerFatura($index)"
                                class="btn btn-sm btn-danger pull-right">
                            <i class="fa fa-remove"></i>
                        </button>

                        <div class="form-group col-sm-12">
                            {!! Form::hidden(('faturas[id][]'), null, ['class' => 'form-control text-right', ':value' => 'fatura.id']) !!}

                            {!! Form::label('numero', 'Numero') !!}
                            {!! Form::text(('faturas[numero][]'), null, ['class' => 'form-control text-right', ':value' => 'fatura.numero']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('numero', 'Numero') !!}
                            {!! Form::date(('faturas[vencimento][]'), null, [
                                'class' => 'form-control text-right',
                                'v-model' => "fatura.vencimento",
                                ':value' => 'fatura.vencimento'
                            ]) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('valor', 'Valor') !!}
                            {!! Form::text(('faturas[valor][]'), null, ['class' => 'form-control text-right', ':value' => 'fatura.valor']) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12" style="margin-top: 20px;">
    @if(empty($notafiscal->status))
    {!! Form::button( '<i class="fa fa-remove"></i> Rejeitar', [
    'class' => 'btn btn-danger pull-right',
        'type'=>'submit',
        'value' => 'Rejeitar',
        'name' => 'acao',
    ]) !!}
    {!! Form::button( '<i class="fa fa-save"></i> Aceite',
    ['class' => 'btn btn-success pull-right',
        'type'=>'submit',
        'value' => 'Aceitar',
        'name' => 'acao'
    ]) !!}
    @endif

    <a href="{!! route('notafiscals.index') !!}" class="btn btn-default">
        <i class="fa fa-times"></i>
        {{ ucfirst( trans('common.cancel') )}}
    </a>
</div>

<div class="modal fade"
     id="conciliacao-modal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="conciliacaoFormLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h4 class="modal-title"
                    id="conciliacaoFormLabel">
                    Conciliar Item: @{{ itemsNf[index].nome_produto }}
                </h4>
            </div>
            <div class="modal-body">

                <div class="clearfix"></div>
                {{ Form::open(['method' => 'post', 'id' => 'conciliacao-form', 'role' => 'form']) }}

                <div class="form-group clearfix">
                    <div class="col-md-12">
                        <select
                                :id='"itemSelecionado"'
                                v-model="itemsNf[index].solicitacao_entrega_item_id"
                                class="form-control select2">
                            <option
                                    v-for="($value, $key) in itemsSolicitacoes"
                                    v-bind:value="$key">
                                @{{ $value }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="clearfix"></div>

                <button type="button" v-on:click="updateVinculo" class="btn btn-sm btn-primary">
                    Vincular
                </button>
                <button type="button" data-dismiss="modal" class="btn btn-sm btn-default">
                    Cancelar
                </button>
                {{ Form::close() }}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@section('scripts')
    <style>
        .box-rounded-bordered {
            border: 1px dashed #ccc;
            border-radius: 3px;
        }
    </style>
    <script>
                <?php
                $faturas = [];
                foreach ($notafiscal->faturas as $fatura) {
                    $faturaItem = $fatura->toArray();
                    unset($faturaItem['vencimento']);
                    $faturaItem['vencimento'] = $fatura->vencimento ? $fatura->vencimento->format("Y-m-d") : null;
                    $faturas[] = $faturaItem;
                }
                ?>

        var $faturasNf = {!! json_encode($faturas)  !!};
        var $itemsNf = {!! json_encode($notafiscal->items()->with('solicitacaoEntregaItem', 'solicitacaoEntregaItem.insumo')->get())  !!};
        var $itemsSolicitacoes = {!! json_encode($itemsSolicitacoes)  !!};

        const app = new Vue({
            el: '#nota_fiscal',
            data: {
                index: 0,
                itemsNf: $itemsNf,
                faturasNf: $faturasNf,
                itemsSolicitacoes: $itemsSolicitacoes
            },
            watch: {},
            methods: {
                desvincular: function ($index) {

                    this.itemsNf[$index].solicitacao_entrega_itens_text = null;
                    this.itemsNf[$index].solicitacao_entrega_itens_id = null;
                    this.itemsNf[$index].solicitacao_entrega_item = null;

                    console.log(this.itemsNf[$index])

                },
                openModal: function ($index) {
                    this.index = $index;
                    $('#conciliacao-modal').modal('show');
                },
                updateVinculo: function () {
                    $itemSelecionadoVal = $('#itemSelecionado').val();
                    $itemSelecionadoText = $('#itemSelecionado option:selected').text();
                    this.itemsNf[this.index].solicitacao_entrega_itens_text = $itemSelecionadoText;
                    this.itemsNf[this.index].solicitacao_entrega_itens_id = $itemSelecionadoVal;
                    $('#itemSelecionado').val('').trigger('change');
                    $('#conciliacao-modal').modal('hide');
                },
                adicionarFatura: function () {
                    this.faturasNf.push({});
                },
                removerFatura: function ($index) {
                    this.faturasNf.splice($index, 1);
                }
            },
            created: function () {
            }
        });
    </script>
@stop
