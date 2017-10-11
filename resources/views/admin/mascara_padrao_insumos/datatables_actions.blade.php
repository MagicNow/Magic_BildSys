{!! Form::open(['route' => ['admin.mascara_padrao_insumos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>	
    {{--<a href="{{ route('admin.mascara_padrao_insumos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>--}}
        {{--<i class="glyphicon glyphicon-edit"></i>--}}
    {{--</a>--}}
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}

</div>
{!! Form::close() !!}