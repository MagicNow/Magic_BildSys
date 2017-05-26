@if($insumo_grupo_id != 1570)
    @if($saldo>0)
        <button type="button" href="#" class="btn btn-xs btn-primary btn-flat"
                onclick="comprarTudo({{$id}},
                                     {{$obra_id}},
                                     {{$grupo_id}},
                                     {{$subgrupo1_id}},
                                     {{$subgrupo2_id}},
                                     {{$subgrupo3_id}},
                                     {{$servico_id}},
                                     '{{$qtd_total}}')">
            <i class="fa fa-usd" aria-hidden="true"></i> Comprar saldo
        </button>
    @endif
@endif