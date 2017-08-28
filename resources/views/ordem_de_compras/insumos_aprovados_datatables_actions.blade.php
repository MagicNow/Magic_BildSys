{!! Form::checkbox('ordem_de_compra_itens[]',$id, null,['id'=>'item_'.$id,'class'=>'item_checks']) !!}
&nbsp;
<button type="button" class="btn btn-xs btn-danger btn-flat" title="Dispensar insumo" onclick="dispensarInsumoAprovado({{ $id }},'{{ $insumo_nome }}')">
    <i class="fa fa-times"></i>
</button>
