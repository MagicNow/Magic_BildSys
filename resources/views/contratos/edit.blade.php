@extends('layouts.front')

@section('content')
    <div class="content">
        {!! Form::model($contrato, ['route' => ['contratos.update', $contrato->id], 'method' => 'patch']) !!}
            <section class="content-header" style="margin-bottom: 20px">
                <h1>
                    Editar contrato #{{$contrato->id}}
                </h1>
            </section>
            <div class="box box-muted">
                <div class="box-body">
                    @if($contrato->itens)
                    <table class="table">
                        <thead class="head-table">
                            <tr>
                                <th class="row-table">DESCRIÇÃO</th>
                                <th class="row-table">QTD</th>
                                <th class="row-table">VALOR UNITÁRIO</th>
                                <th class="row-table">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($contrato->itens as $item)
                            <tr>
                                <th scope="row" class="row-table">{{$item->insumo->nome}}</th>
                                <td class="row-table"><input value="{{number_format($item->qtd, 2, ',', '.')}}" class="form-control money" onkeyup="calculaTotal(this.value, '{{$item->id}}')" name="quantidade[{{$item->id}}][qtd]"></td>
                                <td class="row-table js-calc-valor_unitario-{{$item->id}} money">{{float_to_money($item->valor_unitario)}}</td>
                                <td class="row-table js-calc-valor_total-{{$item->id}}">{{float_to_money($item->valor_total)}}</td>
                                <input type="hidden" value="{{$item->id}}" name="quantidade[{{$item->id}}][id]">
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div class="form-group col-sm-12 pull-right text-right">
                {!! Form::button( '<i class="fa fa-save"></i> Salvar e enviar para aprovação do contrato', [
                                    'class' => 'btn btn-success btn-lg btn-flat',
                                    'style' => 'margin-left:10px',
                                    'type'=>'submit']) !!}
            </div>
        {!! Form::close() !!}
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function calculaTotal(qtd, id) {
            var valor_unitario = $('.js-calc-valor_unitario-'+id).html().replace('R$ ', '');
            var valor_total = $('.js-calc-valor_total-'+id);

            valor_total.html(
                floatToMoney(
                    parseFloat(moneyToFloat(qtd), 10) * moneyToFloat(valor_unitario)
                )
            );
        }
    </script>
@stop
