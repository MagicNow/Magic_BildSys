{!! Form::open(['route' => ['admin.catalogo_contratos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('admin.catalogo_catalogo_contratos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}

</div>
{!! Form::close() !!}
