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
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title ">
                    {{ $qcFornecedor->fornecedor->nome . ' | CNPJ: '.$qcFornecedor->fornecedor->cnpj }}
                </h3>
                <div class="row text-right form-inline">
                        <span class="col-md-4">
                           <label>Template de Contrato</label>
                        </span>
                        <span class="col-md-8 text-left">
                            {!! Form::select('template['.$qcFornecedor->id.']',[''=>'Selecione...']+
                            \App\Models\ContratoTemplate::pluck('nome','id')->toArray(),null,[
                            'class'=>'form-control select2 contratoTemplate',
                            'required'=>'required',
                            'id'=>'contratoTemplate'.$qcFornecedor->id,
                            'qcFornecedor'=>$qcFornecedor->id
                            ]) !!}
                        </span>
                </div>
            </div>
            <div class="box-body">
                <div class="col-md-6">
                    <h4>Itens do Contrato</h4>
                    <table class="table table-striped table-hovered table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th width="70%">Insumo</th>
                                <th width="10%">Qtd.</th>
                                <th width="20%">Valor</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php $total_contrato = 0; ?>
                            @foreach($qcFornecedor->itens as $item)
                                <?php $total_contrato += $item->valor_total; ?>
                                <tr>
                                    <td>{{ $item->qcItem->insumo->nome }}</td>
                                    <td>{{ $item->qcItem->qtd . ' '. $item->qcItem->insumo->unidade_sigla }}</td>
                                    <td>R$ {{ number_format($item->valor_total,2,',','.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="warning">
                                <td colspan="2" class="text-right">Total</td>
                                <td>R$ {{ number_format($total_contrato,2,',','.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-md-6" id="blocoCamposExtras{{ $qcFornecedor->id }}" style="display: none">
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
            <div class="box-footer text-center">
                <button type="submit" class="btn btn-block btn-flat btn-success btn-lg">
                    <i class="fa fa-file"></i> Gerar contrato deste fornecedor
                </button>
            </div>
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
                               campos += '<tr>'+
                                        '   <td class="text-center">'+
                                        '       <label for="'+valor.tag+'">'+valor.nome+'</label>' +
                                        '   </td>'+
                                        '   <td>'+
                                        '       <input type="text" class="form-control" required="required" name="'+valor.tag+'" placeholder="'+valor.nome+'">'+
                                        '   </td>'+
                                        '   <td class="text-center">'+
                                        '       <label for="'+valor.tag+'">'+valor.tipo+'</label>'+
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
    });
</script>
@stop
