{!! Form::open(['route' => ['lpu.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>    
    <a href="{{ route('lpu.edit', $id) }}" title="{{ ucfirst( trans('common.suggest') )}}" class='btn btn-primary btn-xs btn-flat'>
        {{ ucfirst( trans('common.suggest') )}}
    </a>
</div>
{!! Form::close() !!}
