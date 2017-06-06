@extends('layouts.front')

@section('content')
    <section class="content-header">
        <h1>
            Quadro De Concorrencia {{ $quadroDeConcorrencia->id }} -
            Gerar Contrato{{ count($fornecedores) > 1 ?'s': '' }}
            <small class="label label-default pull-right margin10">
                <i class="fa fa-clock-o"
                   aria-hidden="true"></i> {{ $quadroDeConcorrencia->created_at->format('d/m/Y H:i') }}
                <i class="fa fa-user" aria-hidden="true"></i> {{ $quadroDeConcorrencia->user ? $quadroDeConcorrencia->user->name : 'Automático' }}
            </small>

            <small class="label label-info pull-right margin10" id="qc_status">
                <i class="fa fa-circle" aria-hidden="true" style="color:{{ $quadroDeConcorrencia->status->cor }}"></i>
                {{ $quadroDeConcorrencia->status->nome }}
            </small>
        </h1>
    </section>
    <div class="content">
        @foreach($fornecedores as $qcFornecedor)
        {!! Form::open(['id'=>'formFornecedor'.$qcFornecedor->id]) !!}
        <div class="box box-solid" id="boxQcFornecedor{{ $qcFornecedor->id }}">
            {!! Form::hidden('qcFornecedor', $qcFornecedor->id) !!}
            <div class="box-header with-border">
                <h3 class="box-title full-width">

                    {{ $qcFornecedor->fornecedor->nome . ' | CNPJ: '.$qcFornecedor->fornecedor->cnpj }}

                    @if($quadroDeConcorrencia->hasServico())
                        @if($qcFornecedor->porcentagem_servico<100)
                            <span class="pull-right">
                                <span class="label label-info">{{$qcFornecedor->porcentagem_servico }}% Serviço</span>
                                <span class="label label-primary">{{$qcFornecedor->porcentagem_material }}% Material</span>
                                <span class="label label-warning">{{$qcFornecedor->porcentagem_faturamento_direto }}% Fat. Direto</span>
                                <span class="label bg-maroon-active">{{$qcFornecedor->porcentagem_locacao }}% Locação</span>
                            </span>
                        @endif
                    @endif
                </h3>

            </div>
            <div class="box-body">
                @if(isset($contratosExistentes[$qcFornecedor->id]))
                    @if(count($contratosExistentes[$qcFornecedor->id])!=count($total_contrato[$qcFornecedor->id]) )
                        <div class="row text-right form-inline">
                            <span class="col-md-4">
                               <label>Template de Contrato</label>
                            </span>
                            <span class="col-md-8 text-left">
                                {!! Form::select('contrato_template_id',[''=>'Selecione...']+
                                \App\Models\ContratoTemplate::pluck('nome','id')->toArray(),null,[
                                'class'=>'form-control select2 contratoTemplate',
                                'required'=>'required',
                                'id'=>'contratoTemplate'.$qcFornecedor->id,
                                'qcFornecedor'=>$qcFornecedor->id
                                ]) !!}
                            </span>
                        </div>
                    @endif
                @else
                    <div class="row text-right form-inline">
                            <span class="col-md-4">
                               <label>Template de Contrato</label>
                            </span>
                            <span class="col-md-8 text-left">
                                {!! Form::select('contrato_template_id',[''=>'Selecione...']+
                                \App\Models\ContratoTemplate::pluck('nome','id')->toArray(),null,[
                                'class'=>'form-control select2 contratoTemplate',
                                'required'=>'required',
                                'id'=>'contratoTemplate'.$qcFornecedor->id,
                                'qcFornecedor'=>$qcFornecedor->id
                                ]) !!}
                            </span>
                    </div>
                @endif
                <div class="col-md-7">
                    @if(count($contratoItens))
                        @foreach($contratoItens[$qcFornecedor->id] as $obraId => $itens)
                            @if(!isset($contratosExistentes[$qcFornecedor->id][$obraId]))
                            <h4>Contrato Obra {{ \App\Models\Obra::find($obraId)->nome }}</h4>
                            <table class="table table-striped table-hovered table-bordered table-condensed">
                            <thead>
                                <tr>
                                    <th width="60%">Insumo</th>
                                    <th width="20%">Qtd.</th>
                                    <th width="20%">Valor</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                    $frete = 0;
                                    $tem_material = false;
                                ?>
                                @foreach($itens as $item)

                                    @if(!isset($item['frete']))
                                        <?php
                                        if($item['tipo']=='MATERIAL'){
                                            $tem_material = true;
                                        }
                                        ?>
                                        <tr>
                                            <td class="text-left">
                                                <label class="label label-{{ $item['tipo']=='SERVIÇO'?'info':'primary' }}">{{ $item['tipo'] }}</label>
                                                {{ $item['insumo']->nome }}</td>
                                            <td class="text-right">
                                                {{ number_format($item['qtd'],2,',','.') . ' '. $item['insumo']->unidade_sigla }}
                                            </td>
                                            <td class="text-right">
                                                R$ {{ number_format($item['valor_total'],2,',','.') }}
                                            </td>
                                        </tr>

                                    @else
                                        <?php $frete = $item['valor_total']; ?>
                                    @endif

                                @endforeach
                                @if($quadroDeConcorrencia->hasMaterial() && $qcFornecedor->tipo_frete != 'CIF')
                                <tr>
                                    <td colspan="2" class="text-left">Frete</td>
                                    <td class="text-right">
                                        <div class="input-group">
                                            <span class="input-group-addon">R$</span>
                                            <input type="text"
                                                   class="form-control text-right money"
                                                   value="{{ number_format($frete,2,',','.') }}"
                                                   onkeyup="alteraValorTotal('{{ $qcFornecedor->id.'_'. $obraId }}', this.value);"
                                                   name="valor_frete[{{$obraId}}]">
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="warning">
                                    <td colspan="2" class="text-right">TOTAL</td>
                                    <input type="hidden" id="total_contrato_{{ $qcFornecedor->id.'_'. $obraId }}"
                                           value="{{ $total_contrato[$qcFornecedor->id][$obraId] }}">
                                    <td class="text-right" id="sum_total_contrato_{{ $qcFornecedor->id.'_'. $obraId }}">
                                        R$ {{ number_format($total_contrato[$qcFornecedor->id][$obraId]+$frete,2,',','.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                            @else
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h4><i class="icon fa fa-check"></i>
                                        Contrato Obra {{ \App\Models\Obra::find($obraId)->nome }} já gerado!</h4>
                                    <a href="{{ route('contratos.show', $contratosExistentes[$qcFornecedor->id][$obraId]->id) }}" class="btn btn-link btn-flat btn-block">
                                        Exibir contrato
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="col-md-5" id="blocoCamposExtras{{ $qcFornecedor->id }}" style="display: none">
                    <h4>Campos Extras</h4>
                    <table class="table table-condensed table-hovered table-striped table-bordered">
                        <thead>
                            <th width="40%">Campo</th>
                            <th width="40%">Valor</th>
                            <th width="20%">Tipo</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($contratosExistentes[$qcFornecedor->id]))
                @if(count($contratosExistentes[$qcFornecedor->id])!= count($total_contrato[$qcFornecedor->id]))
                    <div class="box-footer text-center">
                        <button type="submit" class="btn btn-block btn-flat btn-success btn-lg">
                            <i class="fa fa-file"></i> Gerar contrato deste fornecedor
                        </button>
                    </div>
                @endif
            @else
                <div class="box-footer text-center">
                    <button type="submit" class="btn btn-block btn-flat btn-success btn-lg">
                        <i class="fa fa-file"></i> Gerar contrato deste fornecedor
                    </button>
                </div>
            @endif
        </div>
        {!! Form::close() !!}
        @endforeach
        <div class="row">
            <a href="{!! route('quadroDeConcorrencias.index') !!}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i>  {{ ucfirst( trans('common.back') )}}
            </a>
        </div>
    </div>
@endsection
@section('scripts')
<script type="text/javascript">
    function alteraValorTotal(qual, valor) {
        total = parseFloat($('#total_contrato_'+qual).val()) + moneyToFloat(valor);
        $('#sum_total_contrato_'+qual).html(floatToMoney(total));
    }
    $(function () {
        $('.contratoTemplate').on('select2:select', function (evt) {
            var qcFornecedor = $(evt.target).attr('qcFornecedor');
            if(parseInt($(evt.target).val())==0){
                $('#blocoCamposExtras'+qcFornecedor).hide();
                return false;
            }
            $.ajax('/contrato-template/'+$(evt.target).val()+'/campos')
                    .done(function (retorno) {
                        var campos = '';
                        console.log(retorno.campos_extras);
                        if(retorno.campos_extras){
                            $.each(retorno.campos_extras, function(index, valor){
                                var v_tag = valor.tag.replace('[','');
                                v_tag = 'CAMPO_EXTRA['+ v_tag.replace(']','') + ']';
                               campos += '<tr>'+
                                        '   <td class="text-center">'+
                                        '       <label for="'+v_tag+'">'+valor.nome+'</label>' +
                                        '   </td>'+
                                        '   <td>'+
                                        '       <input type="text" class="form-control" required="required" name="'+v_tag+'" placeholder="'+valor.nome+'">'+
                                        '   </td>'+
                                        '   <td class="text-center">'+
                                        '       <label for="'+v_tag+'">'+valor.tipo+'</label>'+
                                        '   </td>'+
                                        '</tr>';
                            });
                        }
                        $('#blocoCamposExtras'+qcFornecedor+' tbody').html(campos);
                        $('#blocoCamposExtras'+qcFornecedor).show();
                    })
                    .fail(function (retorno) {
                        swal('Erro', 'Houve um problema ao buscar dados do template','error');
                    });
        });

        $('form').submit(function (event) {
            event.preventDefault();
            var form = $(this);
            $('#'+form.attr('id')+' .box.box-solid').append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

            if (form.attr('id') == '' || form.attr('id') != 'fupload') {
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize()
                }).done(function (retorno) {
                    $('.overlay').remove();
                    console.log(retorno);
                    setTimeout(function () {
                        var contratos_ids = '';
                        var exibir_contratos = '';
                        $.each(retorno.contratos, function (index, contrato) {
                            if(contratos_ids!=''){
                                contratos_ids += ', ';
                            }
                            exibir_contratos += ' <a href="/contratos/'+contrato.id+'" target="_blank" class="btn btn-link btn-flat"> Exibir contrato '+contrato.id+' </a>';
                            contratos_ids += contrato.id;
                        });
                        $('#boxQcFornecedor'+retorno.qcFornecedor+' .box-body').html('<div class="alert alert-success alert-dismissible">'+
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                        '<h4><i class="icon fa fa-check"></i> Contrato '+ contratos_ids + ' gerado!</h4>'+
                        exibir_contratos +
                        '</div>');
                        $('#boxQcFornecedor'+retorno.qcFornecedor+' .box-footer').html('<button type="button" class="btn btn-block btn-flat btn-success btn-lg">'+
                                '<i class="fa fa-print"></i> Imprimir contrato'+
                        '</button>');
                        swal({
                                    title: 'Contrato Gerado',
                                    text: '',
                                    type: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                },
                                function(){
                                    swal.close();
                                });
                    }, 10);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Optionally alert the user of an error here...
                    var textResponse = jqXHR.responseText;
                    var alertText = "Confira as mensagens abaixo:\n\n";
                    var jsonResponse = jQuery.parseJSON(textResponse);

                    $.each(jsonResponse, function (n, elem) {
                        alertText = alertText + elem + "\n";
                    });
                    $('.overlay').remove();
                    swal({title: "", text: alertText, type: 'error'});
                });
            }
            else {
                var formData = new FormData(this);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: formData,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false
                }).done(function (retorno) {
                    $('.overlay').remove();
                    setTimeout(function () {
                        swal({
                                    title: 'Contrato Gerado',
                                    text: '',
                                    type: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                },
                                function(){
                                    swal.close();
                                });
                    }, 10);

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Optionally alert the user of an error here...
                    var textResponse = jqXHR.responseText;
                    var alertText = "Confira as mensagens abaixo:\n\n";
                    var jsonResponse = jQuery.parseJSON(textResponse);

                    $.each(jsonResponse, function (n, elem) {
                        alertText = alertText + elem + "\n";
                    });
                    $('.overlay').remove();
                    swal({title: "", text: alertText, type: 'error'});
                });
            }
            ;
        });
    });
</script>
@stop
