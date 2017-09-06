{!! Form::open(['route' => ['lpu.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>    
    <a href="{{ route('lpu.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
</div>
{!! Form::close() !!}
