<style>
    #parcelasBloco li{
        border-bottom: solid 1px #ccc;
        padding-bottom: 20px;
    }
</style>
{!! Form::hidden('contrato_id', $contrato->id) !!}
<!-- Numero Documento Field -->
<div class="form-group col-sm-6">
    {!! Form::label('numero_documento', 'Numero Documento:') !!}
    {!! Form::number('numero_documento', null, ['class' => 'form-control','required']) !!}
</div>

@if(count($fornecedores)>1)
    <!-- Fornecedor Id Field -->
    <div class="form-group col-sm-6">
        {!! Form::label('fornecedor_id', 'Fornecedor:') !!}
        {!! Form::select('fornecedor_id', [''=>'Selecione...'] + $fornecedores, null, ['class' => 'form-control select2']) !!}
    </div>
@else
    {!! Form::hidden('fornecedor_id',  $contrato->fornecedor_id) !!}
@endif

<!-- Data Emissao Field -->
<div class="form-group col-sm-6">
    {!! Form::label('data_emissao', 'Data Emissão:') !!}
    {!! Form::date('data_emissao', (!isset($pagamento)?null:$pagamento->data_emissao->format('Y-m-d')), ['class' => 'form-control','required']) !!}
</div>

<!-- Valor Field -->
<div class="form-group col-sm-6">
    {!! Form::label('valor', 'Valor:') !!}
    {!! Form::text('valor', null, ['class' => 'form-control money']) !!}

</div>

<!-- Pagamento Condicao Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('pagamento_condicao_id', 'Condição de Pagamento:') !!}
    {!! Form::select('pagamento_condicao_id',[''=>'Selecione...'] + $pagamentoCondicoes ,null, [
        'class' => 'form-control select2',
        'required' => 'required'
        ]) !!}
</div>

<!-- Documento Tipo Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('documento_tipo_id', 'Tipo de Documento Fiscal:') !!}
    {!! Form::select('documento_tipo_id', [''=>'Selecione...'] + $documentoTipos ,
    (isset($pagamento) AND $pagamento != null ? (isset($pagamento) ? $pagamento->documento_tipo_id : 1) : null),
    ['class' => 'form-control select2', 'required']) !!}
</div>

<!-- Notas Fiscal Id Field -->
<div class="form-group col-sm-6">
    @if((isset($nota) and $nota != NULL) || $pagamento->notas_fiscal_id > 0)
    {!! Form::label('notas_fiscal_id', 'Notas Fiscal:') !!}
    <h4><label class="label label-info">{{ isset($nota) ? $nota->codigo : (isset($pagamento) ? $pagamento->notaFiscal->codigo : null) }}</label></h4>
    {!! Form::hidden('notas_fiscal_id', isset($nota) ? $nota->id : (isset($pagamento) ? $pagamento->notas_fiscal_id: null), ['class' => 'form-control']) !!}
    @endif
</div>

<div class="form-group col-sm-12">
        <h4>
            Parcelas
        <button type="button" onclick="adicionaParcela();" class="btn btn-flat btn-primary pull-right btn-xs">
            <i class="fa fa-plus"></i>
            Adicionar parcela
        </button>
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
                <li  id="parcela{{ $qtdParcelas }}">
                    <div class="row">
                        {!! Form::hidden('parcelas['.$qtdParcelas.'][id]', $parcela->id) !!}
                        <div class="col-xs-3">
                            <label>Valor</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][valor]', float_to_money( $parcela->valor, '' ),
                            ['class' => 'form-control money text-right','placeholder'=>'Valor da parcela']) !!}
                        </div>

                        <div class="col-xs-3">
                            <label>Nº Documento</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][numero_documento]', $parcela->numero_documento,
                            ['class' => 'form-control text-right','placeholder'=>'Número do Documento']) !!}
                        </div>

                        <div class="col-xs-2">
                            <label>Data Vencimento</label>
                            {!! Form::date('parcelas['.$qtdParcelas.'][data_vencimento]', $parcela->data_vencimento->format('Y-m-d'),
                            ['class' => 'form-control','placeholder'=>'Vencimento']) !!}
                        </div>

                        <div class="col-xs-1">
                            <label>% Desconto</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][percentual_desconto]', float_to_money( $parcela->percentual_desconto,''),
                            ['class' => 'form-control money text-right','placeholder'=>'% Desconto']) !!}
                        </div>
                        <div class="col-xs-2">
                            <label>Valor Desconto</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][valor_desconto]', float_to_money($parcela->valor_desconto,''),
                            ['class' => 'form-control money text-right','placeholder'=>'Valor desconto']) !!}
                        </div>
                        <div class="col-xs-1 text-right">
                            <button type="button" onclick="removeParcela(this);" class="btn btn-danger btn-xs btn-flat">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px">
                        <div class="col-xs-2">
                            <label>% Juro Mora</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][percentual_juro_mora]', float_to_money($parcela->percentual_juro_mora,''),
                            ['class' => 'form-control money text-right', 'placeholder'=>'% Juro Mora']) !!}
                        </div>
                        <div class="col-xs-3">
                            <label>Valor Juro Mora</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][valor_juro_mora]', float_to_money($parcela->valor_juro_mora,''),
                            ['class' => 'form-control money text-right', 'placeholder'=>'Valor Juro Mora']) !!}
                        </div>
                        <div class="col-xs-2">
                            <label>% Multa</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][percentual_multa]', float_to_money($parcela->percentual_multa,''),
                            ['class' => 'form-control money text-right', 'placeholder'=>'% Multa']) !!}
                        </div>
                        <div class="col-xs-3">
                            <label>Valor Multa</label>
                            {!! Form::text('parcelas['.$qtdParcelas.'][valor_multa]', float_to_money($parcela->valor_multa,''),
                            ['class' => 'form-control money text-right', 'placeholder'=>'Valor Multa']) !!}
                        </div>
                        <div class="col-xs-2">
                            <label>Data Base Multa</label>
                            {!! Form::date('parcelas['.$qtdParcelas.'][data_base_multa]', $parcela->data_base_multa ? $parcela->data_base_multa->format('Y-m-d') : null,
                            ['class' => 'form-control','placeholder'=>'Data Base Multa']) !!}
                        </div>
                    </div>
                </li>
            @endforeach
        @endif
    </ol>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::button( '<i class="fa fa-save"></i> '. ucfirst( trans('common.save') ), ['class' => 'btn btn-lg btn-success pull-right', 'type'=>'submit']) !!}
    <a href="{!! route('pagamentos.index') !!}" class="btn btn-lg btn-danger"><i class="fa fa-times"></i>  {{ ucfirst( trans('common.cancel') )}}</a>
</div>

@section('scripts')
<script type="text/javascript">
    var parcelaNum = {{ $qtdParcelas }};
    function adicionaParcela() {
        parcelaNum ++;
        var parcelaHTML = '<li id="parcela'+parcelaNum+'">\n' +
            '            <div class="row">\n' +
            '                <div class="col-xs-3">\n' +
            '                    <label>Valor</label>\n' +
            '                    <input class="form-control money text-right" placeholder="Valor da parcela" name="parcelas['+parcelaNum+'][valor]" type="text" id="valor">\n' +
            '                </div>\n' +
            '\n' +
            '                <div class="col-xs-3">\n' +
            '                    <label>Nº Documento</label>\n' +
            '                    <input class="form-control text-right" placeholder="Número do Documento" name="parcelas['+parcelaNum+'][numero_documento]" type="text" id="numero_documento">\n' +
            '                </div>\n' +
            '\n' +
            '                <div class="col-xs-2">\n' +
            '                    <label>Data Vencimento</label>\n' +
            '                    <input class="form-control" placeholder="Vencimento" name="parcelas['+parcelaNum+'][data_vencimento]" type="date">\n' +
            '                </div>\n' +
            '\n' +
            '                <div class="col-xs-1">\n' +
            '                    <label>% Desconto</label>\n' +
            '                    <input class="form-control money text-right" placeholder="% Desconto" name="parcelas['+parcelaNum+'][percentual_desconto]" type="text">\n' +
            '                </div>\n' +
            '                <div class="col-xs-2">\n' +
            '                    <label>Valor Desconto</label>\n' +
            '                    <input class="form-control money text-right" placeholder="Valor desconto" name="parcelas['+parcelaNum+'][valor_desconto]" type="text">\n' +
            '                </div>\n' +
            '                <div class="col-xs-1 text-right">\n' +
            '                    <button type="button" onclick="removeParcela(this);" class="btn btn-danger btn-xs btn-flat">\n' +
            '                        <i class="fa fa-times"></i>\n' +
            '                    </button>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '            <div class="row" style="margin-top: 10px">\n' +
            '                <div class="col-xs-2">\n' +
            '                    <label>% Juro Mora</label>\n' +
            '                    <input class="form-control money text-right" placeholder="% Juro Mora" name="parcelas['+parcelaNum+'][percentual_juro_mora]" type="text">\n' +
            '                </div>\n' +
            '                <div class="col-xs-3">\n' +
            '                    <label>Valor Juro Mora</label>\n' +
            '                    <input class="form-control money text-right" placeholder="Valor Juro Mora" name="parcelas['+parcelaNum+'][valor_juro_mora]" type="text">\n' +
            '                </div>\n' +
            '                <div class="col-xs-2">\n' +
            '                    <label>% Multa</label>\n' +
            '                    <input class="form-control money text-right" placeholder="% Multa" name="parcelas['+parcelaNum+'][percentual_multa]" type="text">\n' +
            '                </div>\n' +
            '                <div class="col-xs-3">\n' +
            '                    <label>Valor Multa</label>\n' +
            '                    <input class="form-control money text-right" placeholder="Valor Multa" name="parcelas['+parcelaNum+'][valor_multa]" type="text">\n' +
            '                </div>\n' +
            '                <div class="col-xs-2">\n' +
            '                    <label>Data Base Multa</label>\n' +
            '                    <input class="form-control" placeholder="Data Base Multa" name="parcelas['+parcelaNum+'][data_base_multa]" type="date">\n' +
            '                </div>\n' +
            '            </div>\n' +
            '        </li>';

        $('#parcelasBloco').append(parcelaHTML);

        $('#parcela'+parcelaNum+' .money').maskMoney({
            allowNegative: true,
            thousands: '.',
            decimal: ','
        });

        if (!Modernizr.inputtypes.date) {
            $('#parcela'+parcelaNum+' input[type=date]').each(function(index, obj){
                var data = $(obj).val();
                if(data != '' && (data.indexOf('-')>1)){
                    var ano = data.substring(0, 4);
                    var mes = data.substring(5, 7);
                    var dia = data.substring(8, 10);
                    var data_formatada = dia+'/'+mes+'/'+ano;

                    $(obj).val(data_formatada);
                }
            });

            $('#parcela'+parcelaNum+' input[type=date]')
                .attr('type', 'text')
                .attr('readonly','readonly')
                .datepicker({
                    format: 'dd/mm/yyyy',
                    language:'pt-BR'
                });
        }
    }

    function removeParcela(botao) {
        $(botao).closest('li').remove();
    }
</script>
@stop
