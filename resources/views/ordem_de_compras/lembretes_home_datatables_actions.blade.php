<?php
$icone = '';
if ($dias < 0) {
    $alerta = "danger";
    $icone = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
} elseif ($dias > 30) {
    $alerta = "success";
    $icone = '';
} else {
    $alerta = "warning";
    $icone = '<i class="fa fa-exclamation" aria-hidden="true"></i>';
}
?>
<div class="btn-group">
    <a href="{{ $url }}" class="btn btn-sm btn-flat btn-{{ $alerta }}" style="margin-top: 5px;">
        {!! $icone !!}
        Visualizar
        <i class="fa fa-eye" aria-hidden="true"></i>
    </a>
    <a href="javascript:void(0);" onclick="dispensarInsumos('{{ $url_dispensar }}', 'Deseja realmente dispensar?')" class="btn btn-sm btn-flat btn-warning" style="margin-top: 5px;">
        {!! $icone !!}
        Dispensar
        <i class="fa fa-exclamation" aria-hidden="true"></i>
    </a>
</div>