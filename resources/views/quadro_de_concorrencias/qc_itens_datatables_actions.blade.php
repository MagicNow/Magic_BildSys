@if(!isset($show))
    {!! Form::checkbox('qc_itens[]',$id, null,['id'=>'qc_item_'.$id,'class'=>'qc_item_checks', 'insumo'=>$insumo_nome]) !!}
    @if($oci_qtd>1)
    <button type="button" class="btn btn-xs btn-warning btn-flat" title="Desagrupar" onclick="desagrupar({{$id}})">
        <i class="fa fa-chain-broken" aria-hidden="true"></i>
    </button>
    @endif
@endif