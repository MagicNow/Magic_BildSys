{{--{{dd($qtd_total)}}--}}
@if($saldo>0)
    <button type="button" href="#" class="btn btn-ms btn-flat"
            onclick="comprarTudo({{$id}},
                                 {{$obra_id}},
                                 {{$grupo_id}},
                                 {{$subgrupo1_id}},
                                 {{$subgrupo2_id}},
                                 {{$subgrupo3_id}},
                                 {{$servico_id}},
                                 '{{$qtd_total}}')">
        Comprar saldo
    </button>
@endif