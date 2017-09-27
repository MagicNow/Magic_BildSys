<style>
    #parcelasBloco li{
        border-bottom: solid 1px #ccc;
        padding-bottom: 20px;
    }
</style>
<div>
    <!-- Numero Documento Field -->
    <div class="form-group col-md-4">
        {!! Form::label('numero_documento', 'Número do Documento:') !!}
        <p class="form-control">{!! $pagamento->numero_documento !!}</p>
    </div>

    <!-- Fornecedor Id Field -->
    <div class="form-group col-md-4">
        {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
        <p class="form-control">{!! $pagamento->fornecedor->nome.' - '.$pagamento->fornecedor->cnpj !!}</p>
    </div>

    <!-- Data Emissao Field -->
    <div class="form-group col-md-4">
        {!! Form::label('data_emissao', 'Data Emissao:') !!}
        <p class="form-control">{!! $pagamento->data_emissao->format('d/m/Y') !!}</p>
    </div>
</div>

<div>
    <!-- Valor Field -->
    <div class="form-group col-md-4">
        {!! Form::label('valor', 'Valor:') !!}
        <p class="form-control">{!! float_to_money( $pagamento->valor ) !!}</p>
    </div>

    <!-- Pagamento Condicao Id Field -->
    <div class="form-group col-md-4">
        {!! Form::label('pagamento_condicao_id', 'Condição de Pagamento:') !!}
        <p class="form-control">{!! $pagamento->pagamentoCondicao->codigo !!}</p>
    </div>

    <!-- Documento Tipo Id Field -->
    <div class="form-group col-md-4">
        {!! Form::label('documento_tipo_id', 'Tipo de Documento:') !!}
        <p class="form-control">{!! $pagamento->documentoTipo->sigla !!}</p>
    </div>
</div>
<div>
    <!-- Notas Fiscal Id Field -->
    <div class="form-group col-md-4">
        {!! Form::label('notas_fiscal_id', 'Notas Fiscal:') !!}
        <p class="form-control">{!! $pagamento->notas_fiscal_id !!}</p>
    </div>

    <!-- Enviado Integracao Field -->
    <div class="form-group col-md-4">
        {!! Form::label('enviado_integracao', 'Enviado para Integracao:') !!}
        <p class="form-control">
            <i class="fa fa-{!! $pagamento->enviado_integracao?'check text-success':'times text-danger' !!}"></i>
        </p>
    </div>

    <!-- Integrado Field -->
    <div class="form-group col-md-4">
        {!! Form::label('integrado', 'Integrado:') !!}
        <p class="form-control">
            <i class="fa fa-{!! $pagamento->integrado?'check text-success':'times text-danger' !!}"></i>
        </p>
    </div>
</div>

<div>
    <!-- Created At Field -->
    <div class="form-group col-md-6">
        {!! Form::label('created_at', 'Criado em:') !!}
        <p class="form-control">{!! $pagamento->created_at->format('d/m/Y H:i') !!}</p>
    </div>

    <!-- Updated At Field -->
    <div class="form-group col-md-6">
        {!! Form::label('updated_at', 'Atualizado em:') !!}
        <p class="form-control">{!! $pagamento->updated_at->format('d/m/Y H:i') !!}</p>
    </div>
</div>
@if($pagamento->parcelas)
<div>
    <div class="col-md-12">
        <h4>
            Parcelas
        </h4>
        <ol id="parcelasBloco">
            <?php
            $qtdParcelas = 0;
            ?>
            @if(isset($pagamento))
                @foreach($pagamento->parcelas as $parcela)
                    <?php
                    $qtdParcelas++;
                    ?>
                    <li id="parcela{{ $qtdParcelas }}">
                        <div class="row">
                            {!! Form::hidden('parcelas['.$qtdParcelas.'][id]', $parcela->id) !!}
                            <div class="col-xs-3">
                                <label>Valor</label>
                                <div class="form-control">
                                    {{ float_to_money( $parcela->valor, '' ) }}
                                </div>
                            </div>

                            <div class="col-xs-3">
                                <label>Nº Documento</label>
                                <div class="form-control">
                                    {{ $parcela->numero_documento }}
                                </div>
                            </div>

                            <div class="col-xs-2">
                                <label>Data Vencimento</label>
                                <div class="form-control">
                                    {{ $parcela->data_vencimento->format('Y-m-d') }}
                                </div>
                            </div>

                            <div class="col-xs-1">
                                <label>% Desconto</label>
                                <div class="form-control">
                                    {{ float_to_money( $parcela->percentual_desconto,'') }}
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <label>Valor Desconto</label>
                                <div class="form-control">
                                    {{ float_to_money($parcela->valor_desconto,'') }}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-xs-2">
                                <label>% Juro Mora</label>
                                <div class="form-control">
                                    {{ float_to_money($parcela->percentual_juro_mora,'') }}
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <label>Valor Juro Mora</label>
                                <div class="form-control">
                                    {{ float_to_money($parcela->valor_juro_mora,'') }}
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <label>% Multa</label>
                                <div class="form-control">
                                    {{ float_to_money($parcela->percentual_multa,'') }}
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <label>Valor Multa</label>
                                <div class="form-control">
                                    {{ float_to_money($parcela->valor_multa,'') }}
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <label>Data Base Multa</label>
                                <div class="form-control">
                                    {{ $parcela->data_base_multa ? $parcela->data_base_multa->format('Y-m-d') : '' }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ol>
    </div>

</div>
@endif