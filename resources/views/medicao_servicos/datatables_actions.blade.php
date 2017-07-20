{!! Form::open(['route' => ['medicaoServicos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('medicaoServicos.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if($finalizado && $aprovador)
        <a href="{{ route('medicaoServicos.show', $id) }}" title="Aprovar/Reprovar" class='btn btn-default bg-purple btn-xs'>
            <i class="fa fa-check"></i>
        </a>
    @endif
    @shield('medicoes.edit')
    <a href="{{ route('medicaoServicos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield
    @shield('medicoes.delete')
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-xs',
            'onclick' => "confirmDelete('formDelete".$id."');",
            'title' => ucfirst(trans('common.delete'))
        ]) !!}
    @endshield
</div>
{!! Form::close() !!}
