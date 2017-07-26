{!! Form::open(['route' => ['boletim-medicao.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('boletim-medicao.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if($medicao_boletim_status_id===1)
    <a href="{{ route('boletim-medicao.liberar-nf', $id) }}" title="Autorizar Recebimento de Nota Fiscal" class='btn btn-success btn-xs btn-flat'>
        <i class="glyphicon glyphicon-usd" aria-hidden="true"></i>
    </a>
    <a href="{{ route('boletim-medicao.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endif

</div>
{!! Form::close() !!}
