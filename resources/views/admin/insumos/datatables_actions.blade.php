{{--{!! Form::open(['route' => ['admin.insumos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}--}}
<div class='btn-group'>
    <a href="{{ route('admin.insumos.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    {{--<a href="{{ route('admin.insumos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}--}}

</div>
{{--{!! Form::close() !!}--}}
