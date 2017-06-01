@extends('layouts.front')

@section('content')
    <div class="content">
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
                            <td class="row-table"><input value="{{number_format($item->qtd, 2, ',', '.')}}" class="form-control money" onkeyup="calculaTotal(this.value, '{{$item->id}}')"></td>
                            <td class="row-table js-calc-valor_unitario money-{{$item->id}}">{{float_to_money($item->valor_unitario)}}</td>
                            <td class="row-table js-calc-valor_total-{{$item->id}}">{{float_to_money($item->valor_total)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function calculaTotal(qtd, id) {
            var valor_unitario = $('#js-calc-valor_unitario-'+id).val();
            var valor_total = $('#js-calc-valor_total-'+id);

//            valor_total.innerText = floatToMoney(
//                parseFloat(moneyToFloat(qtd), 10) * moneyToFloat(valor_unitario)
//            );
        }
    </script>
@stop
