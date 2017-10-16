{!! Form::open(['route' => ['admin.mascara_padrao_insumos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "deleteInsumo($id);",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
</div>
{!! Form::close() !!}