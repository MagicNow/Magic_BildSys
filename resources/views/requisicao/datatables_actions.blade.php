{!! Form::open(['route' => ['requisicao.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('requisicao.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route('requisicao.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>

    @if($status != 'Em Separação')

    <a href="{{ route('requisicao.show', $id) }}/update/1" data-toggle="tooltip" data-placement="top" title="Fazer Separação" class='btn btn-default btn-xs'>
        <i class="fa fa-cubes" aria-hidden="true"></i>
    </a>

    @endif

    <a href="{{ route('requisicao.processoSaida', $id) }}" class='btn btn-primary btn-xs' data-toggle="tooltip" data-placement="top" title="Saída de Insumos">
        <i class="glyphicon glyphicon-log-out"></i>
    </a>

    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}

</div>
{!! Form::close() !!}
