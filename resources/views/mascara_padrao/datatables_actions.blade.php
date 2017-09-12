{!! Form::open(['route' => ['mascara_padrao.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}

<div class='btn-group'>    
    @shield('mascara_padrao.edit')
    <a href="{{ route('mascara_padrao.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield
	
    @shield('mascara_padrao.delete')
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endshield
</div>
{!! Form::close() !!}
