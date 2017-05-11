<?php
    $icone = '';
    if($dias < 0){
        $alerta = "danger";
        $icone = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
    }elseif($dias > 30){
        $alerta = "success";
        $icone = '';
    }else{
        $alerta = "warning";
        $icone = '<i class="fa fa-exclamation" aria-hidden="true"></i>';
    }
?>
<a href="{{ $url }}" class="btn btn-sm btn-flat btn-{{ $alerta }}">
    {!! $icone !!}
    Comprar
    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
</a>