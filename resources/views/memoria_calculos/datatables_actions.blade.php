{!! Form::open(['route' => ['memoriaCalculos.destroy', $id], 'id'=>'formDelete'.$id, 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('memoriaCalculos.show', $id) }}" title="{{ ucfirst( trans('common.show') )}}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    @if($padrao)
        @shield('memoriaCalculos.create')
            <a href="{{ route('memoriaCalculos.clone', $id) }}" title="Criar um novo à partir deste" class='btn btn-info btn-xs'>
                <i class="fa fa-clone"></i>
            </a>
        @endshield
    @endif
    @shield('memoriaCalculos.edit')
    <a href="{{ route('memoriaCalculos.edit', $id) }}" title="{{ ucfirst( trans('common.edit') )}}" class='btn btn-warning btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    @endshield
        @if(!intval($utilizado))
        @shield('memoriaCalculos.delete')
            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
                    'type' => 'button',
                    'class' => 'btn btn-danger btn-xs',
                    'onclick' => "confirmDelete('formDelete".$id."');",
                    'title' => ucfirst(trans('common.delete'))
                ]) !!}
            @endshield
    @endif
</div>
{!! Form::close() !!}
