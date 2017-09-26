{!! Form::open(['route' => ['admin.medicao_fisicas.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>    
    <a href="{{ route('admin.medicao_fisicas.edit', $id) }}" title="{{ ucfirst( trans('common.measure') )}}" class='btn btn-success btn-xs btn-flat'>
        {{ ucfirst( trans('common.measure') )}}
    </a>
</div>
{!! Form::close() !!}
